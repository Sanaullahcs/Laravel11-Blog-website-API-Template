<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        $footer = Footer::first();
        if ($footer) {
            return response()->json($footer);
        } else {
            return response()->json(['message' => 'Footer not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_information' => 'required|string',
            'quick_links' => 'required|string',
            'social_media_links' => 'required|string',
            'newsletter_signup' => 'required|string',
        ]);

        $footer = Footer::create($validated);

        return response()->json($footer, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'contact_information' => 'required|string',
            'quick_links' => 'required|string',
            'social_media_links' => 'required|string',
            'newsletter_signup' => 'required|string',
        ]);

        $footer = Footer::find($id);

        if ($footer) {
            $footer->update($validated);
            return response()->json($footer);
        } else {
            return response()->json(['message' => 'Footer not found'], 404);
        }
    }

    public function destroy($id)
    {
        $footer = Footer::find($id);

        if ($footer) {
            $footer->delete();
            return response()->json(['message' => 'Footer deleted successfully']);
        } else {
            return response()->json(['message' => 'Footer not found'], 404);
        }
    }
}
