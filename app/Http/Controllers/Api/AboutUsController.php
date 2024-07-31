<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{

    
    public function index()
    {
        $aboutUs = AboutUs::first();
        if ($aboutUs) {
            return response()->json($aboutUs);
        } else {
            return response()->json(['message' => 'About Us section not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'contact_email' => 'nullable|string|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
        ]);

        $aboutUs = AboutUs::find($id);
        if (!$aboutUs) {
            return response()->json(['message' => 'About Us section not found'], 404);
        }

        // Handle image if present
        if ($request->has('image')) {
            // Delete old image if exists
            if ($aboutUs->image) {
                Storage::delete($aboutUs->image);
            }
            // Store new image
            $imageData = base64_decode($request->input('image'));
            $imageName = 'about_us_images/' . uniqid() . '.png';
            Storage::put($imageName, $imageData);
            $validated['image'] = $imageName;
        }

        $aboutUs->update($validated);

        return response()->json($aboutUs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'contact_email' => 'nullable|string|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
        ]);

        // Handle image if present
        if ($request->has('image')) {
            $imageData = base64_decode($request->input('image'));
            $imageName = 'about_us_images/' . uniqid() . '.png';
            Storage::put($imageName, $imageData);
            $validated['image'] = $imageName;
        }

        $aboutUs = AboutUs::create($validated);

        return response()->json($aboutUs, 201);
    }
}
