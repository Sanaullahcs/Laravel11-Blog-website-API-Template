<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * List all services.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all services from the database
        $services = Service::all();

        // Return a successful response with the list of services
        return response()->json([
            'success' => true,
            'message' => 'Services retrieved successfully.',
            'data' => $services
        ]);
    }

    /**
     * Store a new service.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required|string|max:255',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        // Create a new service with validated data
        $service = Service::create($validated);

        // Return a successful response with the created service
        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
            'data' => $service
        ], 201);
    }

    /**
     * Show a specific service by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the service by ID
        $service = Service::find($id);

        if (!$service) {
            // Return error if service not found
            return response()->json([
                'success' => false,
                'message' => 'Service not found.'
            ], 404);
        }

        // Return a successful response with the service details
        return response()->json([
            'success' => true,
            'message' => 'Service retrieved successfully.',
            'data' => $service
        ]);
    }

    /**
     * Update a specific service by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'sometimes|string|max:255',
        ]);

        // Find the service by ID
        $service = Service::find($id);

        if (!$service) {
            // Return error if service not found
            return response()->json([
                'success' => false,
                'message' => 'Service not found.'
            ], 404);
        }

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                \Storage::disk('public')->delete($service->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        // Update service details
        $service->update($validated);

        // Return a successful response with the updated service
        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'data' => $service
        ]);
    }

    /**
     * Delete a specific service by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the service by ID
        $service = Service::find($id);

        if (!$service) {
            // Return error if service not found
            return response()->json([
                'success' => false,
                'message' => 'Service not found.'
            ], 404);
        }

        // Delete the service image if exists
        if ($service->image) {
            \Storage::disk('public')->delete($service->image);
        }

        // Delete the service record
        $service->delete();

        // Return a successful response indicating deletion
        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.'
        ], 200); // Use 200 OK for successful deletion
    }
}
