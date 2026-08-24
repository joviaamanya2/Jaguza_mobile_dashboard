<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiseaseController extends Controller
{
    public function diagnose(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'animal_type' => 'required|string|max:100',
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $animalType = $this->normalise($request->string('animal_type')->toString());
        $symptoms = collect($request->input('symptoms'))
            ->map(fn ($symptom) => $this->normalise($symptom))
            ->filter()
            ->values();

        $results = Disease::active()->get()->map(function (Disease $disease) use ($animalType, $symptoms) {
            $diseaseText = $this->normalise(implode(' ', [
                (string) $disease->name,
                (string) $disease->species_affected,
                (string) $disease->symptoms,
            ]));
            $matchedSymptoms = $symptoms->filter(function (string $symptom) use ($diseaseText) {
                return str_contains($diseaseText, $symptom)
                    || collect(preg_split('/\s+/', $symptom))
                        ->filter(fn ($word) => strlen($word) > 3)
                        ->contains(fn ($word) => str_contains($diseaseText, $word));
            });

            $speciesText = $this->normalise((string) $disease->species_affected);
            $speciesMatch = str_contains($speciesText, $animalType)
                || ($animalType === 'poultry' && (str_contains($speciesText, 'chicken') || str_contains($speciesText, 'bird')));

            $symptomScore = $symptoms->isEmpty()
                ? 0
                : (int) round(($matchedSymptoms->count() / $symptoms->count()) * 80);
            $score = min(100, $symptomScore + ($speciesMatch ? 20 : 0));

            return [
                'id' => $disease->id,
                'name' => $disease->name,
                'match' => $score,
                'severity' => ucfirst((string) $disease->severity),
                'description' => $disease->symptoms,
                'symptoms' => $disease->symptoms,
                'prevention' => $disease->prevention,
                'treatment' => $disease->treatment,
                'thumbnail' => $disease->thumbnail,
            ];
        })
            ->filter(fn (array $disease) => $disease['match'] > 0)
            ->sortByDesc('match')
            ->take(5)
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'diseases' => $results,
                'matches' => $results->count(),
                'message' => $results->isEmpty()
                    ? 'No close matches found. Please consult a veterinarian.'
                    : 'Matches are based on the symptoms and animal type provided.',
            ],
        ]);
    }

    private function normalise(string $value): string
    {
        return strtolower(trim(preg_replace('/[^a-z0-9]+/i', ' ', $value)));
    }

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
