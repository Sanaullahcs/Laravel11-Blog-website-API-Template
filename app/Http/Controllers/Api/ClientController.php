<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * List all clients.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all clients
        $clients = Client::all();

        // Return a successful response with the list of clients
        return response()->json([
            'success' => true,
            'message' => 'Clients retrieved successfully.',
            'data' => $clients
        ]);
    }

    /**
     * Store a new client.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string',
            'contact_info' => 'required|string',
            'feedback' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048', // Validate image file
        ]);
    
        // Initialize image path
        $imageName = null;
    
        // Handle file upload if an image file is provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
    
            // Create a unique filename for the image
            $imageName = 'clients/images/' . uniqid() . '.' . $image->getClientOriginalExtension();
    
            // Store the image in the 'public' disk
            $image->storeAs('public', $imageName);
        } else {
            // Use a dummy image if no file is uploaded
            $imageName = 'clients/images/dummy.png'; // Path to your dummy image
        }
    
        // Add image path to validated data if an image was processed
        if ($imageName) {
            $validated['image'] = $imageName;
        }
    
        // Create a new client with validated data
        $client = Client::create($validated);
    
        // Return a successful response with the created client
        return response()->json([
            'success' => true,
            'message' => 'Client created successfully',
            'data' => $client
        ], 201);
    }

    /**
     * Show a specific client by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the client by ID
        $client = Client::find($id);
        if (!$client) {
            // Return error if client not found
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 404);
        }

        // Return a successful response with the client
        return response()->json([
            'success' => true,
            'message' => 'Client retrieved successfully.',
            'data' => $client
        ]);
    }

    /**
     * Update a specific client by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string',
            'contact_info' => 'required|string',
            'feedback' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validate image
        ]);
    
        // Find the client by ID
        $client = Client::find($id);
        if (!$client) {
            // Return error if client not found
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 404);
        }
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($client->image && Storage::exists('public/' . $client->image)) {
                Storage::delete('public/' . $client->image);
            }
    
            // Upload new image
            $image = $request->file('image');
            $imagePath = $image->store('clients/images', 'public');
            $validated['image'] = $imagePath;
        }
    
        // Update client details
        $client->update($validated);
    
        // Return a successful response with the updated client
        return response()->json([
            'success' => true,
            'message' => 'Client updated successfully',
            'data' => $client
        ]);
    }

    /**
     * Delete a specific client by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the client by ID
        $client = Client::find($id);
        if (!$client) {
            // Return error if client not found
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 404);
        }

        // Delete the client's image if it exists
        if ($client->image) {
            Storage::delete('public/' . $client->image);
        }

        // Delete the client record
        $client->delete();

        // Return a successful response indicating deletion
        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully'
        ], 200); // Use 200 OK for successful deletion
    }
}
