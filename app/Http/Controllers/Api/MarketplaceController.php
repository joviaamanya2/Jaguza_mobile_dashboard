<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => MarketplaceListing::with('seller')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', MarketplaceListing::CATEGORIES),
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['title', 'description', 'category', 'price', 'location']);
        if ($request->hasFile('image')) {
            $data['images'] = [Storage::disk('public')->url(
                $request->file('image')->store('marketplace', 'public')
            )];
        }
        $listing = MarketplaceListing::create(array_merge($data, [
            'seller_id' => $request->user()->id ?? 1,
            'currency' => 'UGX',
            'status' => 'active',
            'views_count' => 0,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Listing created successfully',
            'data' => $listing
        ], 201);
    }

    public function show($id)
    {
        $listing = MarketplaceListing::with('seller')->find($id);
        if (!$listing) {
            return response()->json(['success' => false, 'message' => 'Listing not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $listing]);
    }

    public function update(Request $request, $id)
    {
        $listing = MarketplaceListing::find($id);
        if (!$listing) {
            return response()->json(['success' => false, 'message' => 'Listing not found'], 404);
        }

        if (!$this->canModify($request, $listing)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'location' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:' . implode(',', MarketplaceListing::STATUSES),
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $listing->update($request->only(['title', 'description', 'price', 'location', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Listing updated successfully',
            'data' => $listing
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $listing = MarketplaceListing::find($id);
        if (!$listing) {
            return response()->json(['success' => false, 'message' => 'Listing not found'], 404);
        }

        if (!$this->canModify($request, $listing)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        $listing->delete();
        return response()->json(['success' => true, 'message' => 'Listing deleted successfully']);
    }

    private function canModify(Request $request, MarketplaceListing $listing): bool
    {
        $user = $request->user();
        if (!$user) {
            return false;
        }
        return $user->role === 'admin' || $listing->seller_id === $user->id;
    }
}
