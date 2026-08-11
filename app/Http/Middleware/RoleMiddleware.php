<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = JWTAuth::user();

        if (!$user || !$user->role || !in_array($user->role->slug, $roles)) {
            return response()->json([
                'meta' => ['status' => 403, 'message' => 'Forbidden'],
                'data' => null,
                'errors' => null,
            ], 403);
        }

        return $next($request);
    }
}
