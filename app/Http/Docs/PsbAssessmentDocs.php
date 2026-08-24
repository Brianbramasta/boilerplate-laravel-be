<?php

namespace App\Http\Docs;

/**
 * Contoh response Scribe untuk PsbAssessmentController.
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(PsbAssessmentDocs::XXX, status: ...)].
 */
class PsbAssessmentDocs
{
    public const INDEX_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data master penilaian"
            },
            "data": [
                {
                    "assessment": {
                        "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                        "name": "Instalasi ODP"
                    },
                    "categories": [
                        {
                            "category": {
                                "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                                "name": "Kualitas"
                            },
                            "total_weight_active": 100,
                            "items": [
                                {
                                    "id": "3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff",
                                    "code": "PN-0001",
                                    "name": "Redaman OPM di ODP",
                                    "criteria_type": "Single Source",
                                    "score": 50,
                                    "status": "Aktif"
                                },
                                {
                                    "id": "4c6f8b0d-2e3f-4c1a-9b2d-2233445566aa",
                                    "code": "PN-0002",
                                    "name": "Sambungan Patchcord ODP-Drop Core",
                                    "criteria_type": "Single Source",
                                    "score": 50,
                                    "status": "Aktif"
                                }
                            ]
                        }
                    ]
                }
            ]
        }
        JSON;

    public const STORE_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 201,
                "message": "Item penilaian berhasil dibuat"
            },
            "data": {
                "id": "3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff",
                "code": "PN-0011",
                "name": "Selisih Redaman Customer - ODP",
                "assessment": {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "name": "Penarikan"
                },
                "category": {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "name": "Kualitas"
                },
                "criteria_type": {
                    "id": "4c6f8b0d-2e3f-4c1a-9b2d-2233445566aa",
                    "code": "CALCULATE",
                    "name": "Kalkulasi"
                },
                "operation": {
                    "id": "1122334455aa-bbcc-ddee-ff00-112233445566",
                    "code": "SUBTRACTION",
                    "symbol": "-",
                    "name": "Pengurangan"
                },
                "score": null,
                "status": "Aktif",
                "mapping": [
                    {
                        "sequence": 1,
                        "step_id": "3",
                        "step_code": "TEK-03",
                        "step_name": "Redaman ODP pada OPM",
                        "variable_key": "redaman_odp",
                        "type_data": "number"
                    },
                    {
                        "sequence": 2,
                        "step_id": "8",
                        "step_code": "TEK-08",
                        "step_name": "Redaman Pelanggan pada OPM",
                        "variable_key": "redaman_pelanggan",
                        "type_data": "number"
                    }
                ],
                "rules": [
                    {
                        "id": "2233445566bb-ccdd-eeff-0011-223344556677",
                        "rule_code": "PN-0011-R1",
                        "rule": "Selisih maksimum 3 dB",
                        "is_active": true
                    }
                ]
            }
        }
        JSON;

    public const SHOW_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil detail item penilaian"
            },
            "data": {
                "id": "3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff",
                "code": "PN-0001",
                "name": "Redaman OPM di ODP",
                "assessment": {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "name": "Instalasi ODP"
                },
                "category": {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "name": "Kualitas"
                },
                "criteria_type": {
                    "id": "4c6f8b0d-2e3f-4c1a-9b2d-2233445566aa",
                    "code": "SINGLE",
                    "name": "Single Source"
                },
                "operation": null,
                "score": 50,
                "status": "Aktif",
                "mapping": [
                    {
                        "sequence": 1,
                        "step_id": "3",
                        "step_code": "TEK-03",
                        "step_name": "Redaman ODP pada OPM",
                        "variable_key": "redaman_odp",
                        "type_data": "number"
                    }
                ],
                "rules": [
                    {
                        "id": "2233445566bb-ccdd-eeff-0011-223344556677",
                        "rule_code": "PN-0001-R1",
                        "rule": "Redaman di luar batas wajar",
                        "is_active": true
                    },
                    {
                        "id": "3344556677cc-ddee-ff00-1122-334455667788",
                        "rule_code": "PN-0001-R2",
                        "rule": "Foto OPM tidak jelas/blur",
                        "is_active": true
                    }
                ]
            }
        }
        JSON;

    public const NOT_FOUND = <<<'JSON'
        {
            "meta": {
                "status": 404,
                "message": "Item penilaian tidak ditemukan"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const UPDATE_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Item penilaian berhasil diupdate"
            },
            "data": {
                "id": "3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff",
                "code": "PN-0001",
                "name": "Redaman OPM di ODP",
                "assessment": {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "name": "Instalasi ODP"
                },
                "category": {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "name": "Kualitas"
                },
                "criteria_type": {
                    "id": "4c6f8b0d-2e3f-4c1a-9b2d-2233445566aa",
                    "code": "SINGLE",
                    "name": "Single Source"
                },
                "operation": null,
                "score": 50,
                "status": "Non Aktif",
                "mapping": [
                    {
                        "sequence": 1,
                        "step_id": "3",
                        "step_code": "TEK-03",
                        "step_name": "Redaman ODP pada OPM",
                        "variable_key": "redaman_odp",
                        "type_data": "number"
                    }
                ],
                "rules": [
                    {
                        "id": "2233445566bb-ccdd-eeff-0011-223344556677",
                        "rule_code": "PN-0001-R1",
                        "rule": "Redaman di luar batas wajar (revisi)",
                        "is_active": true
                    },
                    {
                        "id": "4455667788dd-eeff-0011-2233-445566778899",
                        "rule_code": "PN-0001-R3",
                        "rule": "Standar baru ditambahkan saat edit",
                        "is_active": true
                    }
                ]
            }
        }
        JSON;

    public const STORE_VALIDATION_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Failed Validation"
            },
            "data": null,
            "errors": {
                "name": ["The name field is required."],
                "assessment_id": ["The assessment id field is required."],
                "assessment_category_id": ["The assessment category id field is required."],
                "criteria_type_id": ["The criteria type id field is required."],
                "mapping": ["The mapping field is required."]
            }
        }
        JSON;

    public const STORE_OPERATION_REQUIRED = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Failed Validation"
            },
            "data": null,
            "errors": {
                "operation_id": ["Operasi wajib diisi untuk cara penilaian Kalkulasi."]
            }
        }
        JSON;

    public const STORE_MAPPING_MISMATCH = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Kalkulasi membutuhkan 2 mapping (Input A dan Input B)."
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const STORE_COMPONENT_TYPE_INVALID = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Komponen 'jenis_kabel' bertipe text, tidak dapat dikalkulasi (hanya number/timestamp)."
            },
            "data": null,
            "errors": null
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
