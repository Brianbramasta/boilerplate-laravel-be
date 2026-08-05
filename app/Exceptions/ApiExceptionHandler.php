<?php

namespace App\Exceptions;

use App\Http\Traits\ApiResponse;
use Throwable;
use BadMethodCallException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ApiExceptionHandler
{
    use ApiResponse;

    public function handle(Throwable $e)
    {
        // 🔥 Log hanya error penting (500)
        if ($this->shouldReport($e)) {
            Log::error($e);
        }

        return match (true) {

            $e instanceof ModelNotFoundException =>
            $this->errorResponse('Resource not found', 404),

            $e instanceof QueryException =>
            $this->errorHandle(
                config('app.debug')
                    ? 'Database error: ' . $e->getMessage()
                    : 'Database error',
                500
            ),

            $e instanceof MethodNotAllowedHttpException =>
            $this->errorHandle('Method not allowed', 405),

            $e instanceof AuthenticationException =>
            $this->errorHandle('Unauthenticated', 401),

            $e instanceof AuthorizationException =>
            $this->errorHandle('This action is unauthorized.', 403),

            $e instanceof ValidationException =>
            $this->errorHandle(
                collect($e->errors())->flatten()->first(),
                422
            ),

            // penting: handle error dari service (422, 400, dll)
            $e instanceof HttpException =>
            $this->errorHandle(
                $e->getMessage(),
                $e->getStatusCode()
            ),

            $e instanceof BadMethodCallException =>
            $this->handleBadMethod($e),

            // fallback (500)
            default =>
            $this->errorResponse(
                'Internal Server Error',
                500,
                config('app.debug') ? $e->getMessage() : null
            )
        };
    }

    /**
     * Tentukan error mana yang perlu di-log
     */
    private function shouldReport(Throwable $e): bool
    {
        return match (true) {

            // tidak perlu di-log (error user / bisnis)
            $e instanceof ValidationException,
            $e instanceof AuthenticationException,
            $e instanceof AuthorizationException,
            $e instanceof HttpException && $e->getStatusCode() < 500
            => false,

            // log error server
            default => true,
        };
    }

    /**
     * Handle bad method exception
     */
    private function handleBadMethod(Throwable $e)
    {
        $message = $e->getMessage();

        return match (true) {

            str_contains($message, 'undefined relationship') =>
            $this->errorHandle('Invalid relationship method.', 400),

            str_contains($message, 'undefined method') =>
            $this->errorHandle('Undefined method called.', 400),

            default =>
            $this->errorHandle('Bad method call.', 400),
        };
    }

    /**
     * Standard error response
     */
    private function errorHandle(string $message, int $code)
    {
        return $this->errorResponse($message, $code);
    }
}