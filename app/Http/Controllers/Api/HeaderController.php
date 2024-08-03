<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Header;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    /**
     * Get the header configuration.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve the first header record
        $header = Header::first();

        if ($header) {
            // Return a successful response with the header details
            return response()->json([
                'success' => true,
                'message' => 'Header retrieved successfully.',
                'data' => $header
            ]);
        } else {
            // Return an error if the header is not found
            return response()->json([
                'success' => false,
                'message' => 'Header not found'
            ], 404);
        }
    }

    /**
     * Update or create a header configuration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'logo' => 'required|array',
            'logo.url' => 'required|string',
            'logo.alt_text' => 'required|string',
            'navigation_menu' => 'required|array',
            'navigation_menu.*.name' => 'required|string',
            'navigation_menu.*.link' => 'required|string',
            'search_bar' => 'required|array',
            'search_bar.placeholder' => 'required|string',
            'search_bar.action' => 'required|string',
        ]);

        // Retrieve the first header record or create a new one if not found
        $header = Header::first();

        if ($header) {
            // Update the existing header record
            $header->update($validated);
        } else {
            // Create a new header record
            $header = Header::create($validated);
        }

        // Return a successful response with the updated or created header
        return response()->json([
            'success' => true,
            'message' => $header->wasRecentlyCreated ? 'Header created successfully.' : 'Header updated successfully.',
            'data' => $header
        ]);
    }
}
