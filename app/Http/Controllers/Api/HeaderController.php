<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Header;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    
    public function index()
    {
        $header = Header::first();
        if ($header) {
            return response()->json($header);
        } else {
            return response()->json(['message' => 'Header not found'], 404);
        }
    }

    public function update(Request $request)
    {
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
    
        $header = Header::first();
        if ($header) {
            $header->update($validated);
        } else {
            $header = Header::create($validated);
        }
    
        return response()->json($header);
    }
    
}
