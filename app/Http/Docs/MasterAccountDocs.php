<?php

namespace App\Http\Docs;

/**
 * Contoh response Scribe untuk MasterAccountController.
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(MasterAccountDocs::XXX, status: ...)].
 */
class MasterAccountDocs
{
    public const INDEX_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data master account"
            },
            "data": [
                {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "name": "Superadmin User",
                    "email": "superadmin@example.com",
                    "role": "Superadmin",
                    "status": "Aktif"
                },
                {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "name": "Admin CUNI User",
                    "email": "admin.cuni@example.com",
                    "role": "Admin CUNI",
                    "status": "Aktif"
                }
            ]
        }
        JSON;

    public const STORE_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 201,
                "message": "Account berhasil dibuat"
            },
            "data": {
                "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                "name": "Budi Santoso",
                "email": "budi@example.com",
                "role_id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                "role": "Admin CUNI",
                "is_active": "Aktif"
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
                "email": ["The email field is required."],
                "role_id": ["The role id field is required."],
                "password": ["The password field is required."]
            }
        }
        JSON;

    public const STORE_EMAIL_TAKEN = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Failed Validation"
            },
            "data": null,
            "errors": {
                "email": ["The email has already been taken."]
            }
        }
        JSON;

    public const SHOW_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil detail account"
            },
            "data": {
                "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                "name": "Superadmin User",
                "email": "superadmin@example.com",
                "role_id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                "role": "Superadmin",
                "is_active": "Aktif"
            }
        }
        JSON;

    public const ACCOUNT_NOT_FOUND = <<<'JSON'
        {
            "meta": {
                "status": 404,
                "message": "Account tidak ditemukan"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const UPDATE_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Account berhasil diupdate"
            },
            "data": {
                "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                "name": "Budi Santoso Updated",
                "email": "budi@example.com",
                "role_id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                "role": "Admin CUNI",
                "is_active": "Non Aktif"
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
                "role_id": ["The selected role id is invalid."]
            }
        }
        JSON;

    public const ROLES_DROPDOWN_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil list roles"
            },
            "data": [
                {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "name": "Admin CUNI",
                    "slug": "admin-cuni"
                },
                {
                    "id": "3b5e7a9c-1f2d-4e6b-8a0c-1122334455ff",
                    "name": "Superadmin",
                    "slug": "superadmin"
                }
            ]
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
