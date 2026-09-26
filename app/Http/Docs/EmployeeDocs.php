<?php

namespace App\Http\Docs;

class EmployeeDocs
{
    public const LEAVE_TYPES_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Daftar jenis cuti berhasil diambil"
            },
            "data": {
                "current_page": 1,
                "data": [
                    {
                        "id": 1,
                        "code": "ANNUAL",
                        "name": "Cuti Tahunan",
                        "is_deduct_balance": true
                    }
                ],
                "per_page": 10,
                "to": 1,
                "total": 1
            }
        }
        JSON;

    public const LEAVE_BALANCES_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Saldo cuti berhasil diambil"
            },
            "data": {
                "current_page": 1,
                "data": [
                    {
                        "leave_type_id": 1,
                        "leave_type_name": "Cuti Tahunan",
                        "year": 2026,
                        "total_quota": 12,
                        "used_quota": 2,
                        "remaining_balance": 10
                    }
                ],
                "per_page": 10,
                "to": 1,
                "total": 1
            }
        }
        JSON;

    public const LEAVE_REQUESTS_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Daftar pengajuan cuti berhasil diambil"
            },
            "data": {
                "current_page": 1,
                "data": [
                    {
                        "id": 101,
                        "leave_type_name": "Cuti Tahunan",
                        "start_date": "2026-10-10",
                        "end_date": "2026-10-12",
                        "total_days": 3,
                        "status": "SUBMITTED",
                        "created_at": "2026-09-01T10:00:00Z"
                    }
                ],
                "per_page": 10,
                "to": 1,
                "total": 1
            }
        }
        JSON;

    public const CREATE_LEAVE_REQUEST_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 201,
                "message": "Pengajuan cuti berhasil disubmit"
            },
            "data": {
                "id": 102,
                "leave_type_id": 1,
                "start_date": "2026-10-10",
                "end_date": "2026-10-12",
                "total_days": 3,
                "reason": "Acara keluarga",
                "status": "SUBMITTED"
            }
        }
        JSON;

    public const CREATE_LEAVE_REQUEST_VALIDATION_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Validasi gagal"
            },
            "data": {
                "leave_type_id": ["The leave type id is required."],
                "start_date": ["The start date is required."]
            }
        }
        JSON;

    public const CREATE_LEAVE_REQUEST_OVERLAP_ERROR = <<<'JSON'
        {
            "meta": {
                "status": 422,
                "message": "Periode cuti bentrok dengan pengajuan Anda yang lain."
            },
            "data": null
        }
        JSON;

    public const CREATE_LEAVE_REQUEST_INSUFFICIENT_BALANCE = <<<'JSON'
        {
            "meta": {
                "status": 400,
                "message": "Saldo cuti tidak mencukupi."
            },
            "data": null
        }
        JSON;

    public const CANCEL_LEAVE_REQUEST_SUCCESS = <<<'JSON'
        {
            "meta": {
                "status": 200,
                "message": "Pengajuan cuti berhasil dibatalkan"
            },
            "data": {
                "id": 102,
                "status": "CANCELLED"
            }
        }
        JSON;

    public const CANCEL_LEAVE_REQUEST_NOT_FOUND = <<<'JSON'
        {
            "meta": {
                "status": 404,
                "message": "Data pengajuan cuti tidak ditemukan."
            },
            "data": null
        }
        JSON;

    public const CANCEL_LEAVE_REQUEST_NOT_CANCELLABLE = <<<'JSON'
        {
            "meta": {
                "status": 400,
                "message": "Pengajuan tidak dapat dibatalkan karena status saat ini adalah APPROVED."
            },
            "data": null
        }
        JSON;
}