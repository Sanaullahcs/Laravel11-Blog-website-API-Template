<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Your authentication logic here
        // Example: Check if user is authenticated via token
        if (! $request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
