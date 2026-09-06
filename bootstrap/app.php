<?php

use App\Exceptions\ApiExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'jwt' => \App\Http\Middleware\JwtMiddleware::class,
            'jwt.optional' => \App\Http\Middleware\OptionalJwtMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'api.key' => \App\Http\Middleware\ApiKeyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return app(ApiExceptionHandler::class)->handle($e);
            }

            // fallback untuk web
            return null; // biar Laravel handle default
        });
    })->create();
