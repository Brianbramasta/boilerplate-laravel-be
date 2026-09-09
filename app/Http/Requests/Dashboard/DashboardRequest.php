<?php
// app/Http/Requests/Dashboard/DashboardRequest.php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',
            'branch_id' => 'nullable|string|exists:ref_branches,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'start_date.date' => 'Format tanggal mulai tidak valid',
            'start_date.date_format' => 'Format tanggal mulai harus YYYY-MM-DD',
            'end_date.date' => 'Format tanggal akhir tidak valid',
            'end_date.date_format' => 'Format tanggal akhir harus YYYY-MM-DD',
            'end_date.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai',
            'branch_id.exists' => 'Branch ID tidak ditemukan'
        ];
    }
}
