<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DecisionSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DecisionSupportController extends Controller
{
    // Display a listing of the resources
    public function index(Request $request)
    {
        $query = DecisionSupport::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('topic')) {
            $query->where('sub_category', $request->topic);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $isApiRequest = $request->expectsJson() || $request->is('api/*');
        if ($isApiRequest) $query->where('is_published', true);
        $resources = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($isApiRequest) {
            return response()->json(['success' => true, 'data' => $resources->items(), 'meta' => ['current_page' => $resources->currentPage(), 'last_page' => $resources->lastPage(), 'per_page' => $resources->perPage(), 'total' => $resources->total()]]);
        }
        
        $categories = ['cattle', 'goat', 'sheep', 'poultry', 'pig', 'rabbit'];
        $topics = ['feeding', 'health', 'breeding', 'housing', 'marketing'];

        return view('admin.decision-support.index', compact('resources', 'categories', 'topics'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        $categories = ['cattle', 'goat', 'sheep', 'poultry', 'pig', 'rabbit'];
        $topics = ['feeding', 'health', 'breeding', 'housing', 'marketing'];
        $difficultyLevels = ['Beginner', 'Intermediate', 'Advanced'];
        
        return view('admin.decision-support.create', compact('categories', 'topics', 'difficultyLevels'));
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'category' => ['required', Rule::in(['cattle', 'goat', 'sheep', 'poultry', 'pig', 'rabbit'])],
            'topic' => ['nullable', Rule::in(['feeding', 'health', 'breeding', 'housing', 'marketing'])],
            'difficulty_level' => ['nullable', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) $validated['image'] = $request->file('image')->store('decision-support', 'public');

        $validated['sub_category'] = $validated['topic'] ?? null;
        unset($validated['topic']);
        $validated['difficulty_level'] = $validated['difficulty_level'] ?? 'beginner';

        // Set default values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_published'] = $request->has('is_published') ? true : false;
        $validated['views_count'] = 0;
        $validated['created_by'] = $request->user()->id;
        
        // Generate summary if not provided
        if (empty($validated['summary'])) {
            $validated['summary'] = Str::limit(strip_tags($validated['content']), 200);
        }

        $resource = DecisionSupport::create($validated);

        if ($request->expectsJson() || $request->is('api/*')) return response()->json(['success' => true, 'message' => 'Resource created successfully.', 'data' => $resource], 201);
        return redirect()->route('dashboard', ['page' => 'decision'])->with('success', 'Resource created successfully!');
    }

    public function featured()
    {
        return response()->json(['success' => true, 'data' => DecisionSupport::published()->featured()->latest()->get()]);
    }

    public function show($id)
    {
        $resource = DecisionSupport::published()->find($id);
        if (!$resource) return response()->json(['success' => false, 'message' => 'Resource not found.'], 404);
        $resource->increment('views_count');
        return response()->json(['success' => true, 'data' => $resource->fresh()]);
    }

    public function related($id)
    {
        $resource = DecisionSupport::published()->findOrFail($id);
        return response()->json(['success' => true, 'data' => DecisionSupport::published()->where('category', $resource->category)->where('id', '!=', $resource->id)->latest()->limit(6)->get()]);
    }

    public function byCategory($category)
    {
        return response()->json(['success' => true, 'data' => DecisionSupport::published()->where('category', $category)->latest()->get()]);
    }

    public function topics()
    {
        return response()->json(['success' => true, 'data' => DecisionSupport::published()->whereNotNull('sub_category')->select('sub_category')->distinct()->orderBy('sub_category')->pluck('sub_category')]);
    }

    public function animals()
    {
        $animals = ['cattle', 'goat', 'sheep', 'poultry', 'pig', 'rabbit'];
        return response()->json(['success' => true, 'data' => collect($animals)->map(fn ($animal) => ['name' => ucfirst($animal === 'pig' ? 'pigs' : $animal), 'category' => $animal, 'image_url' => null, 'resource_count' => DecisionSupport::published()->where('category', $animal)->count()])->values()]);
    }

    public function stats()
    {
        return response()->json(['success' => true, 'data' => ['total' => DecisionSupport::published()->count(), 'featured' => DecisionSupport::published()->featured()->count(), 'categories' => DecisionSupport::published()->distinct('category')->count('category')]]);
    }

    // Show the form for editing the specified resource
    public function edit(DecisionSupport $decisionSupport)
    {
        $categories = ['cattle', 'goat', 'sheep', 'poultry', 'pig', 'rabbit'];
        $topics = ['feeding', 'health', 'breeding', 'housing', 'marketing'];
        $difficultyLevels = ['Beginner', 'Intermediate', 'Advanced'];
        
        return view('admin.decision-support.edit', compact('decisionSupport', 'categories', 'topics', 'difficultyLevels'));
    }

    // Update the specified resource in storage
    public function update(Request $request, DecisionSupport $decisionSupport)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'category' => ['required', Rule::in(['cattle', 'goat', 'sheep', 'poultry', 'pig', 'rabbit'])],
            'animal_type' => 'nullable|string|max:100',
            'topic' => ['nullable', Rule::in(['feeding', 'health', 'breeding', 'housing', 'marketing'])],
            'difficulty_level' => ['nullable', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'image_url' => 'nullable|url|max:500',
            'author' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
        ]);

        // Set boolean values
        $validated['sub_category'] = $validated['topic'] ?? null;
        unset($validated['topic']);
        $validated['difficulty_level'] = $validated['difficulty_level'] ?? $decisionSupport->difficulty_level ?? 'beginner';
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_published'] = $request->has('is_published') ? true : false;
        
        // Generate summary if not provided
        if (empty($validated['summary'])) {
            $validated['summary'] = Str::limit(strip_tags($validated['content']), 200);
        }

        $decisionSupport->update($validated);

        return redirect()->route('admin.decision-support.index')
            ->with('success', 'Resource updated successfully!');
    }

    // Remove the specified resource from storage
    public function destroy(DecisionSupport $decisionSupport)
    {
        $decisionSupport->delete();

        return redirect()->route('admin.decision-support.index')
            ->with('success', 'Resource deleted successfully!');
    }

    // Toggle publish status
    public function togglePublish(DecisionSupport $decisionSupport)
    {
        $decisionSupport->is_published = !$decisionSupport->is_published;
        $decisionSupport->save();

        $status = $decisionSupport->is_published ? 'published' : 'unpublished';
        return redirect()->back()
            ->with('success', "Resource {$status} successfully!");
    }

    // Toggle featured status
    public function toggleFeatured(DecisionSupport $decisionSupport)
    {
        $decisionSupport->is_featured = !$decisionSupport->is_featured;
        $decisionSupport->save();

        $status = $decisionSupport->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()
            ->with('success', "Resource {$status} successfully!");
    }
}
