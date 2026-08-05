<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json([
                'meta' => [
                    'status' => 401,
                    'message' => 'Unauthorized',
                ],
                'data' => null,
                'errors' => null,
            ], 401);
        }

        return $next($request);
    }
}
