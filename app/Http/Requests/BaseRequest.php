<?php

namespace App\Http\Requests;

use App\Http\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{

    use ApiResponse;
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessages = $validator->errors();

        throw new HttpResponseException(
            response()->json([
                'meta' => [
                    'status' => 422,
                    'message' => 'Failed Validation',
                ],
                'data' => null,
                'errors' => $errorMessages,
            ], 422)
        );
    }
}
