<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', "unique:mt_users,email,{$userId}"],
            'password' => ['nullable', 'confirmed', 'password'],
            'role' => ['sometimes', 'required', 'in:employee,approver,hr,admin'],
            'manager_id' => ['nullable', 'exists:mt_users,id'],
        ];
    }
}