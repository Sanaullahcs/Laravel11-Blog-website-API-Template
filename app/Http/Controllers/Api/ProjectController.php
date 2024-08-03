<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * List all projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all projects from the database
        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully.',
            'data' => Project::all()
        ]);
    }

    /**
     * Show a specific project by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the project by ID
        $project = Project::find($id);

        if ($project) {
            // Return a successful response with the project details
            return response()->json([
                'success' => true,
                'message' => 'Project retrieved successfully.',
                'data' => $project
            ]);
        } else {
            // Return error if project not found
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }
    }

    /**
     * Store a new project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|string',
            'client_name' => 'nullable|string',
            'project_link' => 'nullable|url',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/projects', 'public');
            $validated['image'] = $imagePath;
        }

        // Create a new project with validated data
        $project = Project::create($validated);

        // Return a successful response with the created project
        return response()->json([
            'success' => true,
            'message' => 'Project created successfully.',
            'data' => $project
        ], 201);
    }

    /**
     * Update a specific project by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'sometimes|required|string',
            'client_name' => 'nullable|string',
            'project_link' => 'nullable|url',
        ]);

        // Find the project by ID
        $project = Project::find($id);

        if ($project) {
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($project->image && Storage::exists('public/' . $project->image)) {
                    Storage::delete('public/' . $project->image);
                }
                $imagePath = $request->file('image')->store('images/projects', 'public');
                $validated['image'] = $imagePath;
            }

            // Update project details
            $project->update($validated);

            // Return a successful response with the updated project
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully.',
                'data' => $project
            ]);
        } else {
            // Return error if project not found
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }
    }

    /**
     * Delete a specific project by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the project by ID
        $project = Project::find($id);

        if ($project) {
            // Delete the image if exists
            if ($project->image && Storage::exists('public/' . $project->image)) {
                Storage::delete('public/' . $project->image);
            }

            // Delete the project record
            $project->delete();

            // Return a successful response indicating deletion
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully.'
            ]);
        } else {
            // Return error if project not found
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }
    }
}
