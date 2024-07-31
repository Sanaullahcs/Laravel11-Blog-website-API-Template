<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::all();
        return response()->json($galleries);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validates image file
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $imagePath = $request->file('image')->store('images', 'public'); // Save the file
    
        $galleryItem = Gallery::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);
    
        return response()->json(['message' => 'Gallery item created successfully', 'gallery' => $galleryItem], 201);
    }
    

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json($gallery);
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image
            Storage::disk('public')->delete($gallery->image);

            // Store the new image
            $imagePath = $request->file('image')->store('images', 'public');
            $gallery->image = $imagePath;
        }

        $gallery->update($request->only('title', 'description', 'image'));

        return response()->json(['message' => 'Gallery updated successfully', 'gallery' => $gallery]);
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        try {
            $galleries = Gallery::where('title', 'like', '%' . $query . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json($galleries);
        } catch (\Exception $e) {
            \Log::error("Error during search: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred during the search.'], 500);
        }
    }
    
}
