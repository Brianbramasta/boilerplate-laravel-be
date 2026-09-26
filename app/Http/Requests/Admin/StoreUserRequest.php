<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:mt_users,email'],
            'password' => ['required', 'confirmed', 'password'],
            'role' => ['required', 'in:employee,approver,hr,admin'],
            'manager_id' => ['nullable', 'exists:mt_users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ];
    }
}