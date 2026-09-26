<?php

namespace App\Http\Traits;

trait ApiResponse
{
    protected function successResponse($data = null, string $message = null, int $code = 200)
    {
        return response()->json([
            'meta' => [
                'status' => $code,
                'message' => $message,
            ],
            'data' => $data,
        ], $code);
    }

    protected function failResponse(string $message = null, int $code = 400, $errors = null)
    {
        return response()->json([
            'meta' => [
                'status' => $code,
                'message' => $message,
            ],
            'data' => null,
            'errors' => $errors,
        ], $code);
    }

    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null)
    {
        return $this->failResponse($message, $code, $errors);
    }

    protected function unauthorizedResponse(string $message = 'Unauthorized access')
    {
        return $this->failResponse($message, 401);
    }

    protected function unprocessableResponse(string $message = 'Unprocessable entity', $errors = null)
    {
        return $this->failResponse($message, 422, $errors);
    }

    protected function serverErrorResponse(string $message = 'Internal server error')
    {
        return $this->failResponse($message, 500);
    }

    protected function tooManyRequestsResponse(string $message = 'Too many requests')
    {
        return $this->failResponse($message, 429);
    }
}