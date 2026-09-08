<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Video::with('category')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_type' => 'nullable|in:video,image',
            'video_url' => 'nullable|url|required_without_all:video_file,image_url,image_file',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm,avi|max:51200',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'category_id' => 'required|exists:video_categories,id',
            'thumbnail_url' => 'nullable|url',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'duration' => 'nullable|string|max:20',
            'platform' => 'nullable|string|max:30',
            'tags' => 'nullable',
            'is_featured' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $imageUrl = $request->input('image_url');
        if ($request->hasFile('image_file')) {
            $imageUrl = Storage::disk('public')->url(
                $request->file('image_file')->store('videos/images', 'public')
            );
        }

        $videoUrl = $request->input('video_url');
        if ($request->hasFile('video_file')) {
            $videoUrl = Storage::disk('public')->url(
                $request->file('video_file')->store('videos', 'public')
            );
        }

        $mediaType = $request->input('media_type');
        if (!in_array($mediaType, ['video', 'image'], true)) {
            $mediaType = $videoUrl ? 'video' : ($imageUrl ? 'image' : 'video');
        }

        $thumbnailUrl = $request->input('thumbnail_url');
        if ($request->hasFile('thumbnail_file')) {
            $thumbnailUrl = Storage::disk('public')->url(
                $request->file('thumbnail_file')->store('videos/thumbnails', 'public')
            );
        }
        $tags = $request->input('tags', []);
        if (is_string($tags)) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $tags))));
        }
        $isPublished = $request->boolean('is_published', true);
        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'media_type' => $mediaType,
            'video_url' => $videoUrl ?? '',
            'image_url' => $imageUrl,
            'thumbnail_url' => $thumbnailUrl,
            'category_id' => $request->category_id,
            'duration' => $request->duration,
            'tags' => $tags,
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
            'slug' => Str::slug($request->title) . '-' . time(),
            'uploaded_by' => $request->user()->id ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video uploaded successfully',
            'data' => $video
        ], 201);
    }

    public function show($id)
    {
        $video = Video::with('category')->find($id);
        if (!$video) {
            return response()->json(['success' => false, 'message' => 'Video not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $video]);
    }

    public function update(Request $request, $id)
    {
        $video = Video::find($id);
        if (!$video) {
            return response()->json(['success' => false, 'message' => 'Video not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'media_type' => 'nullable|in:video,image',
            'video_url' => 'nullable|url',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $video->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Video updated successfully',
            'data' => $video
        ]);
    }

    public function destroy($id)
    {
        $video = Video::find($id);
        if (!$video) {
            return response()->json(['success' => false, 'message' => 'Video not found'], 404);
        }
        $video->delete();
        return response()->json(['success' => true, 'message' => 'Video deleted successfully']);
    }

    public function categories()
    {
        return response()->json([
            'success' => true,
            'data' => VideoCategory::all()
        ]);
    }

    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:video_categories,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $category = VideoCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'order' => (VideoCategory::max('order') ?? 0) + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = VideoCategory::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:video_categories,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    public function destroyCategory($id)
    {
        $category = VideoCategory::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        if ($category->videos()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a category that still has videos assigned to it.'
            ], 422);
        }

        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }

    public function incrementViews($id)
    {
        $video = Video::find($id);
        if (!$video) {
            return response()->json(['success' => false, 'message' => 'Video not found'], 404);
        }
        $video->incrementViews();
        return response()->json(['success' => true, 'message' => 'View incremented successfully']);
    }
}
