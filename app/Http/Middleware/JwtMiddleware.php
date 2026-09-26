<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json([
                'meta' => [
                    'status' => 401,
                    'message' => 'Unauthenticated. Token tidak valid atau tidak ditemukan.',
                ],
                'data' => null,
                'errors' => null,
            ], 401);
        }

        return $next($request);
    }
}