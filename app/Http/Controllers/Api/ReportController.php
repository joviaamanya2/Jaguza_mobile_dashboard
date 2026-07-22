<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SicknessReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => SicknessReport::with('user')->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->normalize($request);

        $validator = Validator::make($data, $this->rules(), $this->messages());

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $report = SicknessReport::create([
            'report_id' => 'SR-' . strtoupper(uniqid()),
            'user_id' => $data['user_id'] ?? $request->user()?->id,
            'affected_animal_type' => $data['affected_animal_type'],
            'affected_animal_count' => $data['affected_animal_count'],
            'symptom_primary' => $data['symptom_primary'],
            'symptom_other' => $data['symptom_other'] ?? null,
            'symptom_duration' => $data['symptom_duration'] ?? null,
            'severity_level' => $data['severity_level'] ?? 'medium',
            'status' => 'open',
            'notes' => $data['notes'] ?? null,
            'attachments' => $data['attachments'] ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sickness report created successfully',
            'data' => $report->load('user')
        ], 201);
    }

    public function show($id)
    {
        $report = SicknessReport::with('user')->find($id);
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $report]);
    }

    public function update(Request $request, $id)
    {
        $report = SicknessReport::find($id);
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        $data = $this->normalize($request);

        $validator = Validator::make($data, $this->rules(true), $this->messages());

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Only overwrite the columns that were actually sent.
        $report->fill(array_intersect_key($data, array_flip([
            'user_id', 'affected_animal_type', 'affected_animal_count',
            'symptom_primary', 'symptom_other', 'symptom_duration',
            'severity_level', 'status', 'notes', 'attachments',
        ])));
        $report->save();

        return response()->json([
            'success' => true,
            'message' => 'Report updated successfully',
            'data' => $report->load('user')
        ]);
    }

    public function destroy($id)
    {
        $report = SicknessReport::find($id);
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        $report->delete();
        return response()->json(['success' => true, 'message' => 'Report deleted successfully']);
    }

    public function resolve(Request $request, $id)
    {
        $report = SicknessReport::find($id);
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        $report->update(['status' => 'resolved']);

        return response()->json([
            'success' => true,
            'message' => 'Report marked as resolved',
            'data' => $report->load('user')
        ]);
    }

    public function stats()
    {
        return response()->json([
            'success' => true,
            'stats' => [
                'open' => SicknessReport::where('status', 'open')->count(),
                'treating' => SicknessReport::where('status', 'treating')->count(),
                'resolved' => SicknessReport::where('status', 'resolved')->count(),
                'total' => SicknessReport::count(),
            ]
        ]);
    }

    /**
     * Normalise incoming payloads. Accepts both the snake_case column names
     * and the camelCase keys the mobile app sends, and coerces severity to the
     * lowercase enum values.
     */
    private function normalize(Request $request): array
    {
        $data = $request->all();

        $aliases = [
            'animalType' => 'affected_animal_type',
            'animalCount' => 'affected_animal_count',
            'primarySymptom' => 'symptom_primary',
            'otherSymptoms' => 'symptom_other',
            'duration' => 'symptom_duration',
            'severity' => 'severity_level',
            'additionalNotes' => 'notes',
        ];
        foreach ($aliases as $from => $to) {
            if (!array_key_exists($to, $data) && array_key_exists($from, $data)) {
                $data[$to] = $data[$from];
            }
        }

        if (isset($data['severity_level']) && is_string($data['severity_level'])) {
            $data['severity_level'] = strtolower($data['severity_level']);
        }

        if (isset($data['attachments']) && is_string($data['attachments'])) {
            $decoded = json_decode($data['attachments'], true);
            $data['attachments'] = is_array($decoded) ? $decoded : array_values(array_filter(
                array_map('trim', preg_split('/[\r\n,]+/', $data['attachments']))
            ));
        }

        return $data;
    }

    private function rules(bool $isUpdate = false): array
    {
        $required = $isUpdate ? 'sometimes|required' : 'required';

        return [
            'user_id' => 'nullable|exists:users,id',
            'affected_animal_type' => $required . '|string|max:255',
            'affected_animal_count' => $required . '|integer|min:1',
            'symptom_primary' => $required . '|string|max:255',
            'symptom_other' => 'nullable|string|max:255',
            'symptom_duration' => 'nullable|string|max:255',
            'severity_level' => 'nullable|in:' . implode(',', SicknessReport::SEVERITY),
            'status' => 'sometimes|in:' . implode(',', SicknessReport::STATUS),
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'string',
        ];
    }

    private function messages(): array
    {
        return [
            'affected_animal_type.required' => 'The affected animal type is required.',
            'affected_animal_count.required' => 'How many animals are affected?',
            'symptom_primary.required' => 'A primary symptom is required.',
        ];
    }
}
