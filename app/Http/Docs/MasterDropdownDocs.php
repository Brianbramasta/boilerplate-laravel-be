<?php

namespace App\Http\Docs;

/**
 * Contoh response Scribe untuk MasterDropdownController.
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(MasterDropdownDocs::XXX, status: ...)].
 */
class MasterDropdownDocs
{
    public const ASSESSMENTS_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data proses"
            },
            "data": [
                { "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab", "name": "Instalasi ODP" },
                { "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd", "name": "Instalasi Customer" },
                { "id": "3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff", "name": "Penarikan" }
            ]
        }
        JSON;

    public const CATEGORIES_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data jenis penilaian"
            },
            "data": [
                { "id": "4c6f8b0d-2e3f-4c1a-9b2d-2233445566aa", "name": "Kualitas" },
                { "id": "1122334455aa-bbcc-ddee-ff00-112233445566", "name": "Kerapian" }
            ]
        }
        JSON;
}
