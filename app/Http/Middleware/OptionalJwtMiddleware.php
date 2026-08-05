<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class OptionalJwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            // Tidak ada token / token invalid / expired — lanjut sebagai guest, jangan block request.
        }

        return $next($request);
    }
}
