<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // Ensure this import is correct


class LogoutController extends Controller
{
    /**
     * Handle user logout and invalidate the session/token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // For Laravel Sanctum
        Auth::guard('web')->logout();
        
        // For Laravel Passport (API Token)
        // Auth::user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

