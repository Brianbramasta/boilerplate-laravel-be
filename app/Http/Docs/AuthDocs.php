<?php

namespace App\Http\Docs;

class AuthDocs
{
    public const LOGIN_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Login berhasil"
            },
            "data": {
                "token": "1|LaravelSanctumTokenExample...",
                "user": {
                    "id": 1,
                    "name": "John Doe",
                    "email": "employee@dasaria.com",
                    "role": "employee"
                }
            }
        }
        JSON;

    public const LOGIN_VALIDATION_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Validasi gagal"
            },
            "data": {
                "email": ["Format email tidak valid.", "Email wajib diisi."],
                "password": ["Password wajib diisi."]
            }
        }
        JSON;

    public const LOGIN_INVALID_CREDENTIALS = <<<'JSON'
        {
            "meta": {
                "status": 401,
                "message": "Email atau password salah"
            },
            "data": null
        }
        JSON;

    public const ME_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Profil berhasil diambil"
            },
            "data": {
                "id": 1,
                "name": "John Doe",
                "email": "employee@dasaria.com",
                "role": "employee",
                "manager_id": 5,
                "created_at": "2026-09-10T10:00:00Z"
            }
        }
        JSON;

    public const ME_UNAUTHORIZED = <<<'JSON'
        {
            "meta": {
                "status": 401,
                "message": "Unauthenticated. Token tidak valid atau tidak ditemukan."
            },
            "data": null
        }
        JSON;

    public const LOGOUT_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Logout berhasil"
            },
            "data": null
        }
        JSON;

    public const LOGOUT_UNAUTHORIZED = <<<'JSON'
        {
            "meta": {
                "status": 401,
                "message": "Unauthenticated. Sesi tidak ditemukan."
            },
            "data": null
        }
        JSON;
}