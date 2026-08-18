<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $workers = Worker::with('farm')
            ->when($request->farm_id, function ($query, $farmId) {
                return $query->where('farm_id', $farmId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $workers
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farm_id' => 'required|exists:farms,id',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'date_joined' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $farm = Farm::findOrFail($request->farm_id);
            
            // Check if user is authorized to add worker to this farm
            if (auth()->user()->role !== 'admin' && $farm->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $worker = Worker::create([
                'farm_id' => $request->farm_id,
                'name' => $request->name,
                'role' => $request->role,
                'phone' => $request->phone,
                'email' => $request->email,
                'date_joined' => $request->date_joined ?? now(),
                'is_active' => $request->is_active ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Worker created successfully',
                'data' => $worker
            ], 201);

        } catch (\Exception $e) {
            Log::error('Worker creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create worker: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $worker = Worker::with('farm')->findOrFail($id);
            
            if (auth()->user()->role !== 'admin' && $worker->farm->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $worker
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Worker not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $worker = Worker::findOrFail($id);

            if (auth()->user()->role !== 'admin' && $worker->farm->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'role' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:20',
                'email' => 'nullable|email|max:255',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $worker->update($request->only([
                'name', 'role', 'phone', 'email', 'is_active'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Worker updated successfully',
                'data' => $worker
            ]);

        } catch (\Exception $e) {
            Log::error('Worker update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update worker: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $worker = Worker::findOrFail($id);

            if (auth()->user()->role !== 'admin' && $worker->farm->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $worker->delete();

            return response()->json([
                'success' => true,
                'message' => 'Worker deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Worker deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete worker: ' . $e->getMessage()
            ], 500);
        }
    }
}
