<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    
    public function index()
    {
        return response()->json(Project::all());
    }

    public function show($id)
    {
        $project = Project::find($id);

        if ($project) {
            return response()->json($project);
        } else {
            return response()->json(['message' => 'Project not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|string',
            'client_name' => 'nullable|string',
            'project_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/projects', 'public');
            $validated['image'] = $imagePath;
        }

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'sometimes|required|string',
            'client_name' => 'nullable|string',
            'project_link' => 'nullable|url',
        ]);

        $project = Project::find($id);

        if ($project) {
            if ($request->hasFile('image')) {
                // Delete the old image
                if ($project->image && Storage::exists('public/' . $project->image)) {
                    Storage::delete('public/' . $project->image);
                }
                $imagePath = $request->file('image')->store('images/projects', 'public');
                $validated['image'] = $imagePath;
            }

            $project->update($validated);
            return response()->json($project);
        } else {
            return response()->json(['message' => 'Project not found'], 404);
        }
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if ($project) {
            // Delete the image
            if ($project->image && Storage::exists('public/' . $project->image)) {
                Storage::delete('public/' . $project->image);
            }

            $project->delete();
            return response()->json(['message' => 'Project deleted successfully']);
        } else {
            return response()->json(['message' => 'Project not found'], 404);
        }
    }
}
