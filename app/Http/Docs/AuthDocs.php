<?php

namespace App\Http\Docs;

/**
 * Contoh response Scribe untuk AuthController.
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(AuthDocs::XXX, status: ...)].
 */
class AuthDocs
{
    public const REGISTER_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 201,
                "message": "User berhasil registrasi"
            },
            "data": {
                "user": {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "name": "Budi Santoso",
                    "email": "budi@example.com",
                    "created_at": "2026-08-05T03:12:45.000000Z",
                    "updated_at": "2026-08-05T03:12:45.000000Z"
                },
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhcGkiLCJzdWIiOiI5ZjFiM2UyYSJ9.abc123signature"
            }
        }
        JSON;

    public const REGISTER_VALIDATION_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Failed Validation"
            },
            "data": null,
            "errors": {
                "name": ["The name field is required."],
                "email": ["The email field is required."],
                "password": ["The password field is required."]
            }
        }
        JSON;

    public const REGISTER_EMAIL_TAKEN = <<<'JSON'
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

    public const REGISTER_SERVER_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 500,
                "message": "Internal Server Error"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const LOGIN_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "User berhasil login"
            },
            "data": {
                "user": {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "name": "Budi Santoso",
                    "email": "budi@example.com",
                    "created_at": "2026-08-05T03:12:45.000000Z",
                    "updated_at": "2026-08-05T03:12:45.000000Z"
                },
                "role": {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "name": "Superadmin",
                    "slug": "superadmin"
                },
                "access": {
                    "master-account": {
                        "view": true,
                        "create": true,
                        "update": true,
                        "delete": true
                    }
                },
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhcGkiLCJzdWIiOiI5ZjFiM2UyYSJ9.abc123signature",
                "expires_in": 3600
            }
        }
        JSON;

    public const LOGIN_VALIDATION_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Failed Validation"
            },
            "data": null,
            "errors": {
                "email": ["The email field is required."],
                "password": ["The password field is required."]
            }
        }
        JSON;

    public const LOGIN_INVALID_CREDENTIALS = <<<'JSON'
        {
            "meta": {
                "status": 401,
                "message": "Email atau password salah"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const LOGIN_ACCOUNT_INACTIVE = <<<'JSON'
        {
            "meta": {
                "status": 403,
                "message": "Akun tidak aktif, silakan hubungi admin"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const LOGIN_SERVER_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 500,
                "message": "Internal Server Error"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const ME_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Berhasil mengambil data user"
            },
            "data": {
                "user": {
                    "id": "9f1b3e2a-4c3d-4a1a-9b2e-1234567890ab",
                    "name": "Budi Santoso",
                    "email": "budi@example.com",
                    "created_at": "2026-08-05T03:12:45.000000Z",
                    "updated_at": "2026-08-05T03:12:45.000000Z"
                },
                "role": {
                    "id": "6a2c4f1b-7d8e-4b3a-9c1d-0987654321cd",
                    "name": "Superadmin",
                    "slug": "superadmin"
                },
                "access": {
                    "master-account": {
                        "view": true,
                        "create": true,
                        "update": true,
                        "delete": true
                    }
                }
            }
        }
        JSON;

    public const UNAUTHORIZED = <<<'JSON'
        {
            "meta": {
                "status": 401,
                "message": "Unauthorized"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const REFRESH_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Token berhasil di-refresh"
            },
            "data": {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhcGkiLCJzdWIiOiI5ZjFiM2UyYSJ9.newtoken456signature",
                "expires_in": 3600
            }
        }
        JSON;

    public const REFRESH_UNAUTHORIZED = <<<'JSON'
        {
            "meta": {
                "status": 401,
                "message": "Token tidak valid atau sudah tidak bisa di-refresh"
            },
            "data": null,
            "errors": null
        }
        JSON;

    public const CHANGE_PASSWORD_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Password berhasil diubah"
            },
            "data": null
        }
        JSON;

    public const CHANGE_PASSWORD_VALIDATION_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Failed Validation"
            },
            "data": null,
            "errors": {
                "current_password": ["The current password field is required."],
                "password": ["The password field confirmation does not match."]
            }
        }
        JSON;

    public const CHANGE_PASSWORD_WRONG_CURRENT = <<<'JSON'
        {
            "meta": {
                "status": 400,
                "message": "Password lama salah"
            },
            "data": null,
            "errors": null
        }
        JSON;
}
