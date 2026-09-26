<?php

namespace App\Exceptions;

use App\Http\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiExceptionHandler
{
    use ApiResponse;

    public function handle(Throwable $e)
    {
        if ($this->shouldReport($e)) {
            \Illuminate\Support\Facades\Log::error($e);
        }

        $message = $e instanceof HttpException ? $e->getMessage() : 'Internal Server Error';
        $code = $e instanceof HttpException ? $e->getStatusCode() : 500;

        if ($e instanceof ModelNotFoundException) {
            return $this->errorResponse('Resource not found', 404);
        }

        if ($e instanceof ValidationException) {
            return $this->failResponse('Validasi gagal', 422, $e->errors());
        }

        if ($e instanceof AuthenticationException) {
            return $this->unauthorizedResponse('Unauthenticated. Token tidak valid atau tidak ditemukan.');
        }

        if ($e instanceof HttpException) {
            if ($code === 401) {
                return $this->unauthorizedResponse('Unauthenticated. Sesi tidak ditemukan.');
            }

            if ($code === 429) {
                return $this->tooManyRequestsResponse('Terlalu banyak percobaan login. Silakan coba lagi dalam 60 detik.');
            }

            return $this->errorResponse($message, $code);
        }

        return $this->errorResponse(
            config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada server.',
            500,
            config('app.debug') ? ['error_detail' => $e->getMessage()] : null
        );
    }

    private function shouldReport(Throwable $e): bool
    {
        return !($e instanceof ValidationException
            || $e instanceof AuthenticationException
            || $e instanceof HttpException && $e->getStatusCode() < 500);
    }
}