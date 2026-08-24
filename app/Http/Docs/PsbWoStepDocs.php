<?php

namespace App\Http\Docs;

/**
 * Contoh response Scribe untuk PsbWoStepController.
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(PsbWoStepDocs::XXX, status: ...)].
 */
class PsbWoStepDocs
{
    public const INDEX_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data psb wo step"
            },
            "data": [
                {
                    "id": 1,
                    "code": "TEK-01",
                    "order": 1,
                    "name": "Mulai Penarikan",
                    "existing_data": ["data"],
                    "status": "Aktif"
                },
                {
                    "id": 3,
                    "code": "TEK-03",
                    "order": 3,
                    "name": "Redaman ODP pada OPM",
                    "existing_data": ["foto"],
                    "status": "Aktif"
                }
            ]
        }
        JSON;

    public const SHOW_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil detail psb wo step"
            },
            "data": {
                "id": 1,
                "code": "TEK-01",
                "order": 1,
                "name": "Mulai Penarikan",
                "existing_data": ["data"],
                "status": "Aktif",
                "description": "Memulai proses penarikan kabel dari ODP ke lokasi pelanggan.",
                "default_component": [
                    { "key": "waktu_mulai", "value": null, "is_active": true }
                ],
                "extend_component": []
            }
        }
        JSON;

    public const NOT_FOUND = <<<'JSON'
        {
            "meta": {
                "status": 404,
                "message": "Data psb wo step tidak ditemukan"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const UPDATE_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengubah data psb wo step"
            },
            "data": {
                "id": 1,
                "code": "TEK-01",
                "order": 1,
                "name": "Mulai Penarikan",
                "existing_data": ["data"],
                "status": "Aktif",
                "description": "lorem ipsum",
                "default_component": [
                    { "key": "waktu_mulai", "value": null, "is_active": false }
                ],
                "extend_component": []
            }
        }
        JSON;

    public const UPDATE_VALIDATION_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Failed Validation"
            },
            "data": null,
            "errors": {
                "description": ["The description field is required."],
                "default_component.0.is_active": ["The default_component.0.is_active field is required when default_component is present."]
            }
        }
        JSON;

    public const FORBIDDEN = <<<'JSON'
        {
            "meta": {
                "status": 403,
                "message": "Forbidden"
            },
            "data": null,
            "errors": null
        }
        JSON;
}
