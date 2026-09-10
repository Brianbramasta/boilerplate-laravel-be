<?php

namespace App\Http\Requests\WorkOrder;

use App\Http\Requests\BaseRequest;

class UpdateStatusValidationWorkOrderRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'status' => ['required', 'string'], // sesuaikan sesuai kebutuhan status
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Status wajib diisi.',
            'status.string' => 'Status harus berupa teks.',
        ];
    }
}
