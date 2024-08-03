<?php

namespace App\Http\Controllers\Api;

use App\Models\ContactSubmission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    /**
     * Store a new contact submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Create a new contact submission record
        $contactSubmission = ContactSubmission::create($validated);

        // Return a successful response with the created record
        return response()->json([
            'success' => true,
            'message' => 'Contact submission created successfully',
            'data' => $contactSubmission
        ], 201);
    }

    /**
     * List all contact submissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all contact submissions
        $submissions = ContactSubmission::all();

        // Return a successful response with the list of submissions
        return response()->json([
            'success' => true,
            'message' => 'Contact submissions retrieved successfully.',
            'data' => $submissions
        ]);
    }

    /**
     * Show a specific contact submission by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the contact submission by ID or fail with a 404 error
        $submission = ContactSubmission::find($id);
        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Contact submission not found'
            ], 404);
        }

        // Return a successful response with the contact submission
        return response()->json([
            'success' => true,
            'message' => 'Contact submission retrieved successfully.',
            'data' => $submission
        ]);
    }

    /**
     * Update a specific contact submission by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find the contact submission by ID or fail with a 404 error
        $submission = ContactSubmission::find($id);
        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Contact submission not found'
            ], 404);
        }

        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'message' => 'sometimes|string',
        ]);

        // Update the contact submission record
        $submission->update($validated);

        // Return a successful response with the updated record
        return response()->json([
            'success' => true,
            'message' => 'Contact submission updated successfully',
            'data' => $submission
        ]);
    }

    /**
     * Delete a specific contact submission by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the contact submission by ID or fail with a 404 error
        $submission = ContactSubmission::find($id);
        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Contact submission not found'
            ], 404);
        }

        // Delete the contact submission record
        $submission->delete();

        // Return a successful response indicating deletion
        return response()->json([
            'success' => true,
            'message' => 'Contact submission deleted successfully'
        ], 200); // Use 200 OK since we're returning a success message
    }
}
