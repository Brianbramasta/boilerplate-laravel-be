<?php

namespace App\Http\Requests\Master\Account;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreMasterAccountRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role_id' => [
                'required',
                'uuid',
                Rule::exists('mt_roles', 'id')->where(function ($q) {
                    $q->whereIn('slug', ['admin-cuni', 'superadmin']);
                }),
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
