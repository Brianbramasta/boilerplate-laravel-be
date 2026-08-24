<?php

namespace App\Http\Docs;

/**
 * Contoh response Scribe untuk RefController.
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(RefDocs::XXX, status: ...)].
 */
class RefDocs
{
    public const CRITERIA_TYPES_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data criteria types"
            },
            "data": [
                {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "code": "SINGLE",
                    "name": "Single Source"
                },
                {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "code": "CALCULATE",
                    "name": "Kalkulasi"
                }
            ]
        }
        JSON;

    public const CALCULATION_OPERATIONS_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data calculation operations"
            },
            "data": [
                {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "code": "ADDITION",
                    "symbol": "+",
                    "name": "Penambahan"
                },
                {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "code": "SUBTRACTION",
                    "symbol": "-",
                    "name": "Pengurangan"
                }
            ]
        }
        JSON;
}
