<?php

namespace App\Http\Requests\Master\Account;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateMasterAccountRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'role_id' => [
                'sometimes',
                'uuid',
                Rule::exists('mt_roles', 'id')->where(function ($q) {
                    $q->whereIn('slug', ['admin-cuni', 'superadmin']);
                }),
            ],
            'is_active' => ['sometimes', 'boolean'],
            'password' => ['sometimes', 'nullable', 'string', 'min:6'],
        ];
    }
}
