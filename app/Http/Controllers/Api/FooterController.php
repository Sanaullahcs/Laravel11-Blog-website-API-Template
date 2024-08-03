<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    /**
     * Get the footer configuration.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve the first footer record
        $footer = Footer::first();

        if ($footer) {
            // Return a successful response with the footer details
            return response()->json([
                'success' => true,
                'message' => 'Footer retrieved successfully.',
                'data' => $footer
            ]);
        } else {
            // Return an error if the footer is not found
            return response()->json([
                'success' => false,
                'message' => 'Footer not found'
            ], 404);
        }
    }

    /**
     * Store a new footer configuration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'contact_information' => 'required|string',
            'quick_links' => 'required|string',
            'social_media_links' => 'required|string',
            'newsletter_signup' => 'required|string',
        ]);

        // Create a new footer with the validated data
        $footer = Footer::create($validated);

        // Return a successful response with the newly created footer
        return response()->json([
            'success' => true,
            'message' => 'Footer created successfully.',
            'data' => $footer
        ], 201);
    }

    /**
     * Update an existing footer configuration.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'contact_information' => 'required|string',
            'quick_links' => 'required|string',
            'social_media_links' => 'required|string',
            'newsletter_signup' => 'required|string',
        ]);

        // Find the footer record by ID
        $footer = Footer::find($id);

        if ($footer) {
            // Update the existing footer record
            $footer->update($validated);
            // Return a successful response with the updated footer
            return response()->json([
                'success' => true,
                'message' => 'Footer updated successfully.',
                'data' => $footer
            ]);
        } else {
            // Return an error if the footer is not found
            return response()->json([
                'success' => false,
                'message' => 'Footer not found'
            ], 404);
        }
    }

    /**
     * Delete a footer configuration.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the footer record by ID
        $footer = Footer::find($id);

        if ($footer) {
            // Delete the footer record
            $footer->delete();
            // Return a successful response
            return response()->json([
                'success' => true,
                'message' => 'Footer deleted successfully.'
            ]);
        } else {
            // Return an error if the footer is not found
            return response()->json([
                'success' => false,
                'message' => 'Footer not found'
            ], 404);
        }
    }
}
