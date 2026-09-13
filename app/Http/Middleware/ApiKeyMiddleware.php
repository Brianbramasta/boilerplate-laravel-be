<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil API key dari header X-API-KEY
        $apiKey = $request->header('X-API-KEY');

        if (empty($apiKey) || !$this->isValidApiKey($apiKey)) {
            return response()->json([
                'meta' => [
                    'status' => 401,
                    'message' => 'Unauthorized'
                ],
                'data' => null,
                'errors' => null
            ], 401);
        }


        return $next($request);
    }

    /**
     * Validasi API key
     * 
     * @param string $apiKey
     * @return bool
     */
    private function isValidApiKey(string $apiKey): bool
    {
        // Method 1: Validasi dengan array statis
        $validKeys = [
            'abc123xyz456',
            'def789uvw012',
            'ghi345jkl678'
        ];

        // Ambil valid API key dari config/services.php 'erp.key'
        $validKeys = [];
        $erpKey = config('services.erp.key');
        if (!empty($erpKey)) {
            $validKeys[] = $erpKey;
        }

        return in_array($apiKey, $validKeys);

        // Method 2: Validasi dengan database (opsional)
        // return \App\Models\ApiKey::where('key', $apiKey)->where('is_active', true)->exists();

        // Method 3: Validasi dengan environment variable (opsional)
        // return $apiKey === config('app.api_key');
    }
}
