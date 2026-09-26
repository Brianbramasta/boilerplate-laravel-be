<?php

namespace App\Http\Requests\Approver;

use Illuminate\Foundation\Http\FormRequest;

class RejectLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes' => ['required', 'string', 'min:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'Catatan/alasan penolakan wajib diisi.',
        ];
    }
}