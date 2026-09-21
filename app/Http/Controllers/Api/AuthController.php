<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use App\Models\UserActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:15',
            'role' => 'nullable|in:farmer,vet',
            'farm_name' => 'nullable|string|max:255',
            'farm_location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirm_password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
           
            'farm_name' => $request->farm_name,
            'farm_location' => $request->farm_location,
        ]);

        // Log activity
        UserActivityLog::log($user->id, 'register', 'auth', ['email' => $user->email]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // The mobile app accepts either an email address or phone number.
            'email' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $identifier = trim((string) $request->input('email'));
        $user = User::where('email', $identifier)
            ->orWhere('phone_number', $identifier)
            ->first();

        if (!$user || !Hash::check((string) $request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials'
            ], 401);
        }

        // Log activity
        UserActivityLog::log($user->id, 'login', 'auth');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        
        // Log activity
        UserActivityLog::log($user->id, 'logout', 'auth');
        
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load(['doctorProfile', 'farms']);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'farm_name' => 'nullable|string|max:255',
            'farm_location' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $path = $image->store('profiles', 'public');
            $request->merge(['profile_image' => $path]);
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    // Minutes a password reset code stays valid after being sent.
    const RESET_CODE_TTL_MINUTES = 10;

    /**
     * Step 1 of "forgot password": email a 6-digit verification code to the
     * given address, if it belongs to a registered account.
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $email = trim((string) $request->input('email'));
        $user = User::where('email', $email)->first();

        // Respond the same way whether or not the account exists, so this
        // endpoint can't be used to discover which emails are registered.
        if ($user) {
            $code = (string) random_int(100000, 999999);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                ['token' => Hash::make($code), 'created_at' => now()]
            );

            Mail::to($email)->send(new PasswordResetCodeMail($code, $user->name));
        }

        return response()->json([
            'success' => true,
            'message' => 'If an account exists for that email, a verification code has been sent.',
        ]);
    }

    /**
     * Step 2 of "forgot password": check the code the user typed in without
     * consuming it, so the app can move to the "new password" screen.
     */
    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$this->resetCodeIsValid($request->input('email'), $request->input('code'))) {
            return response()->json([
                'success' => false,
                'message' => 'That code is invalid or has expired.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code verified'
        ]);
    }

    /**
     * Step 3 of "forgot password": verify the code again and set the new
     * password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $email = trim((string) $request->input('email'));

        if (!$this->resetCodeIsValid($email, $request->input('code'))) {
            return response()->json([
                'success' => false,
                'message' => 'That code is invalid or has expired.'
            ], 422);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Account not found'
            ], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // The code is single-use: remove it so it can't be replayed.
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        UserActivityLog::log($user->id, 'password_reset', 'auth');

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * Check a submitted code against the hashed code stored for that email,
     * enforcing the expiry window.
     */
    private function resetCodeIsValid(string $email, string $code): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', trim($email))
            ->first();

        if (!$record || !$record->created_at) {
            return false;
        }

        if (Carbon::parse($record->created_at)->addMinutes(self::RESET_CODE_TTL_MINUTES)->isPast()) {
            return false;
        }

        return Hash::check($code, $record->token);
    }
}
