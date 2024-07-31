<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $contactSubmission = ContactSubmission::create($validated);

        return response()->json($contactSubmission, 201);
    }

    public function index()
    {
        $submissions = ContactSubmission::all();
        return response()->json($submissions);
    }

    public function show($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        return response()->json($submission);
    }

    public function update(Request $request, $id)
    {
        $submission = ContactSubmission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'message' => 'sometimes|string',
        ]);

        $submission->update($validated);

        return response()->json($submission);
    }

    public function destroy($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->delete();

        return response()->json(null, 204);
    }
}
