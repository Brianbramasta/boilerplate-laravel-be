<?php

namespace App\Http\Traits;


trait ApiResponse
{
    /**
     * Success Response with Meta Structure
     */
    protected function successResponse($data = null, string $message = null, int $code = 200)
    {
        return response()->json([
            'meta' => [
                'status' => $code,
                'message' => $message
            ],
            'data' => $data
        ], $code);
    }

    /**
     * Fail Response with Meta Structure
     */
    protected function failResponse(string $message = null, int $code = 400, $errors = null)
    {
        return response()->json([
            'meta' => [
                'status' => $code,
                'message' => $message
            ],
            'data' => null,
            'errors' => $errors
        ], $code);
    }

    /**
     * Created Response (201)
     */
    protected function createdResponse($data = null, string $message = 'Resource created successfully')
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Updated Response (200)
     */
    protected function updatedResponse($data = null, string $message = 'Resource updated successfully')
    {
        return $this->successResponse($data, $message, 200);
    }

    /**
     * Deleted Response (200)
     */
    protected function deletedResponse(string $message = 'Resource deleted successfully')
    {
        return $this->successResponse(null, $message, 200);
    }

    /**
     * Not Found Response (404)
     */
    protected function notFoundResponse(string $message = 'Resource not found')
    {
        return $this->failResponse($message, 404);
    }

    /**
     * Unprocessable Entity Response (422)
     */
    protected function unprocessableResponse(string $message = 'Unprocessable entity', $errors = null)
    {
        return $this->failResponse($message, 422, $errors);
    }

    /**
     * Conflict Response (409)
     */
    protected function conflictResponse(string $message = 'Resource conflict')
    {
        return $this->failResponse($message, 409);
    }

    /**
     * Bad Request Response (400)
     */
    protected function badRequestResponse(string $message = 'Bad request')
    {
        return $this->failResponse($message, 400);
    }

    /**
     * Unauthorized Response (401)
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized access')
    {
        return $this->failResponse($message, 401);
    }

    /**
     * Forbidden Response (403)
     */
    protected function forbiddenResponse(string $message = 'Forbidden access')
    {
        return $this->failResponse($message, 403);
    }

    /**
     * Server Error Response (500)
     */
    protected function serverErrorResponse(string $message = 'Internal server error')
    {
        return $this->failResponse($message, 500);
    }

    /**
     * Service Unavailable Response (503)
     */
    protected function serviceUnavailableResponse(string $message = 'Service unavailable')
    {
        return $this->failResponse($message, 503);
    }

    /**
     * Too Many Requests Response (429)
     */
    protected function tooManyRequestsResponse(string $message = 'Too many requests')
    {
        return $this->failResponse($message, 429);
    }

    /**
     * General Error Response
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null)
    {
        return $this->failResponse($message, $code, $errors);
    }

    /**
     * Domain Error Response
     */
    protected function domainErrorResponse(
        string $message = 'Error',
        int $code = 400,
        $errors = null
    ) {

        if ($code < 400 || $code > 599) {
            $code = 500;
            $message = 'Internal Server Error';
        }

        return $this->failResponse(
            $message,
            $code,
            $errors
        );
    }

    /**
     * No Content Response (204)
     */
    protected function noContentResponse()
    {
        return response()->json(null, 204);
    }

    /**
     * Paginated Response with Meta Structure
     */
    protected function paginatedResponse($data, string $message = null, array $extra = [])
    {
        $payload = [
            'current_page' => $data->currentPage(),
            'data' => $data->items(),
            'per_page' => $data->perPage(),
            'to' => $data->lastItem(),
            'total' => $data->total()
        ];

        if (!empty($extra)) {
            $payload = array_merge($payload, $extra);
        }

        return response()->json([
            'meta' => [
                'status' => 200,
                'message' => $message,
                'pagination' => $payload
            ],
            'data' => $data->items()
        ]);
    }
}
