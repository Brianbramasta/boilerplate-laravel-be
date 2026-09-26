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
            $user = JWTAuth::parseToken()->authenticate();
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

        if (!$user) {
            return response()->json([
                'meta' => [
                    'status' => 401,
                    'message' => 'Unauthenticated. Token tidak valid atau tidak ditemukan.',
                ],
                'data' => null,
                'errors' => null,
            ], 401);
        }

        auth()->setUser($user);

        return $next($request);
    }
}