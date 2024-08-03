<?php

namespace App\Http\Controllers\Api;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * List all gallery items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $galleries = Gallery::all(); // Retrieve all gallery items
        return response()->json([
            'success' => true,
            'message' => 'Galleries retrieved successfully.',
            'data' => $galleries // Return gallery items
        ]);
    }

    /**
     * Store a new gallery item.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validates image file
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 400);
        }

        $imagePath = $request->file('image')->store('images', 'public'); // Save the file

        $galleryItem = Gallery::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gallery item created successfully',
            'gallery' => $galleryItem // Return created gallery item
        ], 201);
    }

    /**
     * Show a specific gallery item by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $gallery = Gallery::find($id); // Find gallery item by ID
        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery item not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Gallery item retrieved successfully.',
            'gallery' => $gallery // Return gallery item
        ]);
    }

    /**
     * Update a specific gallery item by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id); // Find gallery item by ID
        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery item not found'
            ], 404);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Gallery updated successfully',
            'gallery' => $gallery // Return updated gallery item
        ]);
    }

    /**
     * Delete a specific gallery item by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $gallery = Gallery::find($id); // Find gallery item by ID
        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery item not found'
            ], 404);
        }

        // Delete the image file
        Storage::disk('public')->delete($gallery->image);

        $gallery->delete(); // Delete the gallery item

        return response()->json([
            'success' => true,
            'message' => 'Gallery item deleted successfully'
        ], 200); // Use 200 OK since we're returning a success message
    }

    /**
     * Search gallery items by title.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        try {
            $galleries = Gallery::where('title', 'like', '%' . $query . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Search results retrieved successfully.',
                'data' => $galleries // Return search results
            ]);
        } catch (\Exception $e) {
            \Log::error("Error during search: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during the search.'
            ], 500); // Internal server error for unexpected issues
        }
    }
}
