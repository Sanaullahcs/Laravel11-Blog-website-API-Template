<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Get a list of all blogs, ordered by creation date.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Blogs retrieved successfully.',
            'data' => $blogs
        ]);
    }

    /**
     * Store a new blog.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        $blog = Blog::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully.',
            'data' => $blog
        ], 201);
    }

    /**
     * Show a single blog by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $blog = Blog::find($id);

        if ($blog) {
            return response()->json([
                'success' => true,
                'message' => 'Blog retrieved successfully.',
                'data' => $blog
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found.'
            ], 404);
        }
    }

    /**
     * Update an existing blog by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $blog = Blog::find($id);

        if ($blog) {
            $data = $request->all();

            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($blog->image && \Storage::exists('public/' . $blog->image)) {
                    \Storage::delete('public/' . $blog->image);
                }
                $imagePath = $request->file('image')->store('images', 'public');
                $data['image'] = $imagePath;
            }

            $blog->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Blog updated successfully.',
                'data' => $blog
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found.'
            ], 404);
        }
    }

    /**
     * Delete a blog by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if ($blog) {
            // Delete the image if it exists
            if ($blog->image && \Storage::exists('public/' . $blog->image)) {
                \Storage::delete('public/' . $blog->image);
            }

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found.'
            ], 404);
        }
    }

    /**
     * Search blogs based on title or content.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $blogs = Blog::where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Blogs search results retrieved successfully.',
            'data' => $blogs
        ]);
    }

    /**
     * Get a paginated list of blogs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Paginated blogs retrieved successfully.',
            'data' => $blogs
        ]);
    }
}
