<?php

namespace App\Exceptions;

use App\Exceptions\ApiExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    // kalau mau, bisa override property $dontReport dan $dontFlash sesuai kebutuhan

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return app(ApiExceptionHandler::class)->handle($exception);
        }

        return parent::render($request, $exception);
    }
}