<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SlideController extends Controller
{
    // Get all slides
    public function index()
    {
        return response()->json(Slide::all(), 200);
    }

    // Store a new slide
    public function store(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
                'enabled' => 'required|boolean',
            ]);

            // Cast 'enabled' to boolean
            $validated['enabled'] = filter_var($validated['enabled'], FILTER_VALIDATE_BOOLEAN);

            // Store the uploaded image
            $path = $request->file('image')->store('slides', 'public');
            $validated['image_path'] = $path;

            // Create the slide
            $slide = Slide::create($validated);

            return response()->json($slide, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'messages' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Slide store error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create slide.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Update an existing slide
    public function update(Request $request, $id)
    
    {
        $slide = Slide::findOrFail($id);
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'enabled' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            // Store new image
            $path = $request->file('image')->store('slides', 'public');
            $validated['image_path'] = $path;
            
        }
    
        $slide->update($validated);
    
        return response()->json($slide);
    }
    


    // Delete an existing slide
    public function destroy(Slide $slide)
    {
        try {
            // Delete image from storage
            if ($slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
                Storage::disk('public')->delete($slide->image_path);
            }

            // Delete slide from database
            $slide->delete();

            return response()->json(['message' => 'Slide deleted.'], 204);

        } catch (\Throwable $e) {
            // Log the error
            Log::error('Slide delete error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to delete slide.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
