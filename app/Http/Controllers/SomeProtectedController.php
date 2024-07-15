<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SomeProtectedController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'message' => 'This is a protected route, accessible only to authenticated users.',
            'user' => $request->user(),
        ]);
    }
}
