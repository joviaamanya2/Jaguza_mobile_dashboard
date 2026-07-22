<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FarmController extends Controller
{
    public function index(Request $request)
    {
        $farms = Farm::with('user')
            ->when($request->user()->role !== 'admin', function ($query) use ($request) {
                return $query->where('user_id', $request->user()->id);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $farms
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'size' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'established_year' => 'nullable|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $farm = Farm::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'owner_name' => $request->owner_name,
                'location' => $request->location,
                'size' => $request->size,
                'description' => $request->description,
                'established_year' => $request->established_year,
                'coordinates' => $request->coordinates,
                'facilities' => $request->facilities,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Farm created successfully',
                'data' => $farm,
                'stats' => $this->farmStats(),
            ], 201);

        } catch (\Exception $e) {
            Log::error('Farm creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create farm: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $farm = Farm::with(['user', 'animals'])->findOrFail($id);
            
            if (auth()->user()->role !== 'admin' && $farm->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'farm' => $farm,
                    'total_animals' => $farm->animals->count(),
                    'animal_stats' => $farm->animals->groupBy('type')->map->count(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Farm not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $farm = Farm::findOrFail($id);

            if (auth()->user()->role !== 'admin' && $farm->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'location' => 'sometimes|string|max:255',
                'owner_name' => 'sometimes|string|max:255',
                'size' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'established_year' => 'nullable|string|max:255',
                'coordinates' => 'nullable|string|max:255',
                'facilities' => 'nullable|array',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $farm->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Farm updated successfully',
                'data' => $farm,
                'stats' => $this->farmStats(),
            ]);

        } catch (\Exception $e) {
            Log::error('Farm update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update farm: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $farm = Farm::findOrFail($id);

            if (auth()->user()->role !== 'admin' && $farm->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $farm->delete();

            return response()->json([
                'success' => true,
                'message' => 'Farm deleted successfully',
                'stats' => $this->farmStats(),
            ]);

        } catch (\Exception $e) {
            Log::error('Farm deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete farm: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stats(Request $request)
    {
        $query = Farm::query();
        
        if ($request->user()->role !== 'admin') {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $query->count(),
                'active' => (clone $query)->where('is_active', true)->count(),
                'inactive' => (clone $query)->where('is_active', false)->count(),
            ]
        ]);
    }

    private function farmStats(): array
    {
        $query = Farm::query();

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return [
            'total' => $query->count(),
            'active' => (clone $query)->where('is_active', true)->count(),
        ];
    }
}
