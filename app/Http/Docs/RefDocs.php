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

    public const BRANCHES_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data branch"
            },
            "data": [
                {
                "id": 1,
                "branch_name": "Temas",
                "kode_branch": "G",
                "address": "Jl. Dewi Sartika I No.13, Temas, Kec. Batu, Kota Batu, Jawa Timur 65315",
                "regional": "Batu",
                "company_name": "GriyaNet"
                },
                {
                "id": 7,
                "branch_name": "Pasuruan Selatan",
                "kode_branch": "PS",
                "address": "Jl. Panglima Sudirman No.90, Pasuruan, Jawa Timur",
                "regional": "Pasuruan",
                "company_name": "Dasarata"
                }
            ]
        }
        JSON;

    public const KATIMS_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data katim"
            },
            "data": [
                {
                "id": 1,
                "nama_team": "Gajah",
                "kategori_team": "PSB",
                "leader_nip": 108022513,
                "leader_name": "Andi",
                "is_active": 1
                },
                {
                "id": 2,
                "nama_team": "Ikan",
                "kategori_team": "PSB",
                "leader_nip": 108022514,
                "leader_name": "Bima",
                "is_active": 1
                },
                {
                "id": 3,
                "nama_team": "Tapir",
                "kategori_team": "PSB",
                "leader_nip": 108022515,
                "leader_name": "Putra",
                "is_active": 1
                },
                {
                "id": 4,
                "nama_team": "Rusa",
                "kategori_team": "PSB",
                "leader_nip": 108022513,
                "leader_name": "Damar",
                "is_active": 1
                },
                {
                "id": 5,
                "nama_team": "Macan",
                "kategori_team": "PSB",
                "leader_nip": 108022517,
                "leader_name": "Gilang",
                "is_active": 0
                }
            ]
        }
        JSON;
}
