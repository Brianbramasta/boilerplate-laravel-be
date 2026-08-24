<?php

namespace App\Http\Requests\Master\PsbVariable;

use App\Http\Requests\BaseRequest;

class UpdatePsbWoStepRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],

            // default_component: hanya status (is_active) per item yang bisa diubah —
            // key harus sudah ada, tidak bisa menambah/menghapus item lewat sini.
            'default_component' => ['sometimes', 'array'],
            'default_component.*.key' => ['required_with:default_component', 'string'],
            'default_component.*.is_active' => ['required_with:default_component', 'boolean'],

            // extend_component: kirim daftar penuh item yang masih ingin aktif.
            // Item baru (key belum ada) otomatis ditambahkan; item lama yang
            // sebelumnya terlihat tapi tidak disebut lagi otomatis soft delete
            // (deleted_at diisi) — tidak perlu flag `deleted` eksplisit.
            'extend_component' => ['sometimes', 'array'],
            'extend_component.*.key' => ['required_with:extend_component', 'string'],
            'extend_component.*.value' => ['sometimes', 'nullable', 'string'],
            'extend_component.*.is_active' => ['required_with:extend_component', 'boolean'],
        ];
    }
}
