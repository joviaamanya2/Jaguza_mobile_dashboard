<?php

namespace App\Http\Controllers\Api;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AnimalController extends Controller

{
    // Keep validation independent of model constants so older deployments do
    // not fail with "Undefined constant App\\Models\\Animal::TYPES".
    private const ANIMAL_TYPES = [
        'cattle', 'cow', 'goat', 'goats', 'sheep', 'pig', 'pigs', 'poultry', 'chicken', 'rabbit', 'rabbits', 'horse', 'horses', 'fish', 'other'
    ];

    private const GENDERS = ['male', 'female'];

    private const HEALTH_STATUSES = [
        'healthy', 'sick', 'treatment', 'quarantine', 'recovering', 'critical'
    ];

    public function index()
    {
        $animals = Animal::with(['farm', 'owner'])->get();
        return response()->json([
            'success' => true,
            'data' => $animals
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Convert empty strings to null for optional fields
        foreach (['identification_number', 'name', 'breed', 'gender', 'age', 'weight', 'health_status', 'photo', 'date_bought', 'purchase_price', 'notes', 'owner_id'] as $field) {
            if (isset($data[$field]) && is_string($data[$field]) && trim($data[$field]) === '') {
                $data[$field] = null;
            }
        }

        // Standardize type
        if (isset($data['type'])) {
            $data['type'] = strtolower(trim($data['type']));
        }

        // Standardize gender
        if (isset($data['gender'])) {
            $data['gender'] = strtolower(trim($data['gender']));
        }

        $validator = Validator::make($data, [
            'identification_number' => 'nullable|string|unique:animals,identification_number',
            'name' => 'nullable|string|max:255',
            'type' => 'required|string',
            'breed' => 'nullable|string|max:255',
            'gender' => 'nullable|in:' . implode(',', self::GENDERS),
            'age' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'nullable|string',
            'farm_id' => 'required|exists:farms,id',
            'owner_id' => 'nullable|exists:users,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'date_bought' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Set owner_id to the authenticated user if not provided
        if (!isset($data['owner_id']) && auth()->user()) {
            $data['owner_id'] = auth()->user()->id;
        }

        $animal = Animal::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Animal created successfully',
            'id' => $animal->id,
            'data' => $animal
        ], 201);
    }


    public function show($id)
    {
        $animal = Animal::with(['farm', 'owner'])->findOrFail($id);
        return response()->json($animal);
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'identification_number' => 'nullable|string|unique:animals,identification_number,' . $id,
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|in:' . implode(',', self::ANIMAL_TYPES),
            'breed' => 'nullable|string|max:255',
            'gender' => 'nullable|in:' . implode(',', self::GENDERS),
            'age' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'nullable|string|in:' . implode(',', self::HEALTH_STATUSES),
            'farm_id' => 'sometimes|required|exists:farms,id',
            'owner_id' => 'sometimes|required|exists:users,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'date_bought' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($animal->photo) {
                Storage::disk('public')->delete($animal->photo);
            }
            $path = $request->file('photo')->store('animals', 'public');
            $data['photo'] = $path;
        }

        $animal->update($data);
        return response()->json($animal);
    }

    public function destroy($id)
    {
        $animal = Animal::findOrFail($id);
        
        // Delete photo if exists
        if ($animal->photo) {
            Storage::disk('public')->delete($animal->photo);
        }
        
        $animal->delete();
        return response()->json(null, 204);
    }

    // Additional endpoints
    public function getByType($type)
    {
        $animals = Animal::with(['farm', 'owner'])
            ->ofType($type)
            ->get();
        return response()->json($animals);
    }

    public function getActive()
    {
        $animals = Animal::with(['farm', 'owner'])
            ->active()
            ->get();
        return response()->json($animals);
    }

    public function getHealthy()
    {
        $animals = Animal::with(['farm', 'owner'])
            ->healthy()
            ->get();
        return response()->json($animals);
    }
}
