<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtensionWorker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ExtensionWorkerController extends Controller
{
    /**
     * Display a listing of extension workers.
     */
    public function index(Request $request)
    {
        $workers = ExtensionWorker::with('user')
            ->orderBy('created_at', 'desc')
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('expertise_area', 'like', "%{$search}%")
                  ->orWhere('assigned_region', 'like', "%{$search}%");
            })
            ->when($request->expertise, function ($query, $expertise) {
                return $query->where('expertise_area', $expertise);
            })
            ->when($request->available, function ($query) {
                return $query->where('is_available', true);
            })
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $workers
        ]);
    }

    /**
     * Store a newly created extension worker.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'expertise_area' => 'required|string|max:255',
            'education_level' => 'required|string|max:255',
            'assigned_region' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'years_of_experience' => 'nullable|integer|min:0',
            'languages_spoken' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create user account with default password
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password123'),
                'role' => 'extension_worker',
                'is_active' => true,
            ]);

            // Create extension worker profile
            $worker = ExtensionWorker::create([
                'user_id' => $user->id,
                'expertise_area' => $request->expertise_area,
                'education_level' => $request->education_level,
                'assigned_region' => $request->assigned_region,
                'phone_number' => $request->phone_number,
                'years_of_experience' => $request->years_of_experience ?? 0,
                'languages_spoken' => $request->languages_spoken,
                'is_available' => true,
                'rating' => 0,
                'total_farm_visits' => 0,
                'bio' => $request->bio,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Extension worker added successfully',
                'data' => $worker->load('user')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Extension worker creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create extension worker: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified extension worker.
     */
    public function show($id)
    {
        try {
            $worker = ExtensionWorker::with('user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $worker
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Extension worker not found'
            ], 404);
        }
    }

    /**
     * Update the specified extension worker.
     */
    public function update(Request $request, $id)
    {
        try {
            $worker = ExtensionWorker::with('user')->findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $worker->user_id,
                'expertise_area' => 'sometimes|string|max:255',
                'education_level' => 'sometimes|string|max:255',
                'assigned_region' => 'sometimes|string|max:255',
                'phone_number' => 'sometimes|string|max:20',
                'years_of_experience' => 'nullable|integer|min:0',
                'languages_spoken' => 'nullable|string|max:255',
                'is_available' => 'sometimes|boolean',
                'bio' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update user
            $userData = [];
            if ($request->has('name')) {
                $userData['name'] = $request->name;
            }
            if ($request->has('email')) {
                $userData['email'] = $request->email;
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            if (!empty($userData)) {
                $worker->user->update($userData);
            }

            // Update worker
            $workerData = $request->only([
                'expertise_area',
                'education_level',
                'assigned_region',
                'phone_number',
                'years_of_experience',
                'languages_spoken',
                'is_available',
                'bio'
            ]);
            
            $worker->update($workerData);

            return response()->json([
                'success' => true,
                'message' => 'Extension worker updated successfully',
                'data' => $worker->fresh('user')
            ]);

        } catch (\Exception $e) {
            Log::error('Extension worker update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update extension worker: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified extension worker.
     */
    public function destroy($id)
    {
        try {
            $worker = ExtensionWorker::findOrFail($id);
            
            // Delete the user account too (cascade will handle worker)
            $worker->user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Extension worker deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Extension worker deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete extension worker: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update extension worker availability.
     */
    public function updateAvailability(Request $request, $id)
    {
        try {
            $worker = ExtensionWorker::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'is_available' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $worker->update(['is_available' => $request->is_available]);

            return response()->json([
                'success' => true,
                'message' => 'Extension worker availability updated',
                'data' => $worker
            ]);

        } catch (\Exception $e) {
            Log::error('Availability update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update availability'
            ], 500);
        }
    }

    /**
     * Get extension worker statistics.
     */
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total' => ExtensionWorker::count(),
                'available' => ExtensionWorker::where('is_available', true)->count(),
                'busy' => ExtensionWorker::where('is_available', false)->count(),
                'top_rated' => ExtensionWorker::with('user')
                    ->orderBy('rating', 'desc')
                    ->limit(5)
                    ->get(),
            ]
        ]);
    }
}

