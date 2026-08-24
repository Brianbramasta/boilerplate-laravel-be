<?php

namespace App\Http\Requests\Master\PsbVariable;

use App\Http\Requests\BaseRequest;

class UpdatePsbWoStepRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
            'default_component' => ['sometimes', 'array'],
            'default_component.*.key' => ['required_with:default_component', 'string'],
            'default_component.*.is_active' => ['required_with:default_component', 'boolean'],
            'default_component.*.type_data' => ['sometimes', 'string', 'in:image,datetime,date,timestamp,text,number'],
        ];
    }
}
