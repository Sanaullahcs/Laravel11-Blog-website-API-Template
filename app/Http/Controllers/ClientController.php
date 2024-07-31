<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    
    public function index()
    {
        return Client::all();
    }

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
            if ($request->input('image') === 'aaa.png') {
                $imageName = 'clients/images/dummy.png'; // Path to your dummy image
            } else {
                // No image was provided; set to null or a default image if needed
                $imageName = 'clients/images/dummy.png'; // Path to your dummy image
            }
        }
    
        // Add image path to validated data if an image was processed
        if ($imageName) {
            $validated['image'] = $imageName;
        }
    
        // Create a new client with validated data
        $client = Client::create($validated);
    
        return response()->json($client, 201);
    }
    
    
    
    
    
    
    public function show($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

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
            return response()->json(['message' => 'Client not found'], 404);
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
    
        return response()->json($client);
    }
    
    
    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        if ($client->image) {
            Storage::delete('public/clients/' . $client->image);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted successfully']);
    }
}
