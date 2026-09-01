<?php

namespace App\Http\Requests\WorkOrder;

use App\Http\Requests\BaseRequest;

class IndexWorkOrderRequest extends BaseRequest
{
    /**
     * Hanya mendefinisikan typenya/struktur data yang diharapkan saja (tanpa nilai default).
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'sort_by' => 'nullable|string|max:100',
            'sort_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:1000',
            'page' => 'nullable|integer|min:1',

            'filter' => 'nullable|array',
            'filter.*' => 'nullable|string',

            'filter_column' => 'nullable|array',
            'filter_column.range' => 'nullable|array',
        ];
    }
}
