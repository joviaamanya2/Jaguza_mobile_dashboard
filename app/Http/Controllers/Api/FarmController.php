<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FarmController extends Controller
{
    public function index(Request $request)
    {
        $farms = Farm::with(['user', 'animals', 'workers'])
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
            'name' => 'required_without:farm_name|string|max:255',
            'farm_name' => 'required_without:name|string|max:255',
            'location' => 'required_without:farm_location|string|max:255',
            'farm_location' => 'required_without:location|string|max:255',
            'owner_name' => 'required_without:farm_owner|string|max:255',
            'farm_owner' => 'required_without:owner_name|string|max:255',
            'size' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'established_year' => 'nullable|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $facilities = $request->input('facilities');
            if (is_string($facilities)) {
                $facilities = json_decode($facilities, true) ?: [];
            }

            $data = [
                'user_id' => $request->user()->id,
                'name' => $request->input('name', $request->input('farm_name')),
                'owner_name' => $request->input('owner_name', $request->input('farm_owner')),
                'location' => $request->input('location', $request->input('farm_location')),
                'size' => $request->size,
                'description' => $request->description,
                'established_year' => $request->established_year,
                'coordinates' => $request->coordinates,
                'facilities' => $facilities,
                'is_active' => true,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('farms', 'public');
            }

            $farm = Farm::create($data);

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
                'farm_name' => 'sometimes|string|max:255',
                'location' => 'sometimes|string|max:255',
                'farm_location' => 'sometimes|string|max:255',
                'owner_name' => 'sometimes|string|max:255',
                'farm_owner' => 'sometimes|string|max:255',
                'size' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'established_year' => 'nullable|string|max:255',
                'coordinates' => 'nullable|string|max:255',
                'facilities' => 'nullable',
                'image' => 'nullable|image|max:2048',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            if ($request->has('facilities') && is_string($request->input('facilities'))) {
                $data['facilities'] = json_decode($request->input('facilities'), true) ?: [];
            }

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('farms', 'public');
            }

            if (!array_key_exists('name', $data) && $request->has('farm_name')) {
                $data['name'] = $request->input('farm_name');
            }

            if (!array_key_exists('location', $data) && $request->has('farm_location')) {
                $data['location'] = $request->input('farm_location');
            }

            if (!array_key_exists('owner_name', $data) && $request->has('farm_owner')) {
                $data['owner_name'] = $request->input('farm_owner');
            }

            $farm->update($data);

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
