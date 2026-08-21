<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiseaseController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Disease::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:diseases',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'species_affected' => 'required|string',
            'symptoms' => 'required|string',
            'transmission' => 'nullable|string',
            'prevention' => 'nullable|string',
            'treatment' => 'required|string',
            'severity' => 'required|in:' . implode(',', Disease::SEVERITIES),
            'outbreak_risk' => 'required|in:' . implode(',', Disease::RISKS),
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->except('thumbnail');
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('diseases', 'public');
        }
        $disease = Disease::create(array_merge($data, ['is_active' => true]));

        if (!$request->expectsJson()) {
            return redirect()->route('dashboard', ['page' => 'disease'])
                ->with('success', 'Disease added successfully.');
        }

        return response()->json([
            'success' => true,
            'message' => 'Disease created successfully',
            'data' => $disease
        ], 201);
    }

    public function show($id)
    {
        $disease = Disease::find($id);
        if (!$disease) {
            return response()->json(['success' => false, 'message' => 'Disease not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $disease]);
    }

    public function update(Request $request, $id)
    {
        $disease = Disease::find($id);
        if (!$disease) {
            return response()->json(['success' => false, 'message' => 'Disease not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:diseases,name,' . $id,
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'species_affected' => 'required|string',
            'symptoms' => 'required|string',
            'transmission' => 'nullable|string',
            'prevention' => 'nullable|string',
            'treatment' => 'required|string',
            'severity' => 'required|in:' . implode(',', Disease::SEVERITIES),
            'outbreak_risk' => 'required|in:' . implode(',', Disease::RISKS),
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->except('thumbnail');
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('diseases', 'public');
        }
        $disease->update($data);

        if (!$request->expectsJson()) {
            return redirect()->route('dashboard', ['page' => 'disease'])
                ->with('success', 'Disease updated successfully.');
        }

        return response()->json([
            'success' => true,
            'message' => 'Disease updated successfully',
            'data' => $disease
        ]);
    }

    public function destroy($id)
    {
        $disease = Disease::find($id);
        if (!$disease) {
            return response()->json(['success' => false, 'message' => 'Disease not found'], 404);
        }

        $disease->delete();
        return response()->json(['success' => true, 'message' => 'Disease deleted successfully']);
    }
}
