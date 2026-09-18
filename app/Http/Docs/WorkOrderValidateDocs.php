<?php

namespace App\Http\Docs;

/**
 * Response Scribe untuk ValidationController (Work Order - Validation).
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(ValidationWoDocs::XXX, status: 200)].
 */
class WorkOrderValidateDocs
{
    /**
     * Response sukses untuk endpoint index() — List data validasi WO (paginated).
     */
    public const LIST_VALIDATION_WO = <<<'JSON'
{
    "meta": {
        "status": 200,
        "message": "Berhasil mengambil data validasi WO"
    },
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": "01a09f2a-39e8-71c5-82d5-44a34fd21a20",
                "pppoe_secret": "budi@griyanet",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Temas",
                "customer_name": "Budi Santoso",
                "splitter": "1/2/1",
                "team_name": "Gajah",
                "katim": "Andi",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-14T09:05:39.000000Z"
            },
            {
                "id": "01a0a425-10a9-71a1-8baa-381b5a50150a",
                "pppoe_secret": "marwan@mail.com",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Temas",
                "customer_name": "marwan",
                "splitter": "1/2/1",
                "team_name": "Tapir",
                "katim": "Putra",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-15T08:18:07.000000Z"
            },
            {
                "id": "01a0a454-4bcf-7346-a06d-4082560e9ccb",
                "pppoe_secret": "ivel@dasaria.id",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Temas",
                "customer_name": "ivel",
                "splitter": "1/2/1",
                "team_name": "Tapir",
                "katim": "Putra",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": "iveeel baik",
                "status_at": "2026-09-15T16:51:02.000000Z"
            },
            {
                "id": "01a0a457-04c1-71c8-8229-068ecd08cf3b",
                "pppoe_secret": "phizura@dasaria.id",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Temas",
                "customer_name": "phizura",
                "splitter": "1/2/1",
                "team_name": "Tapir",
                "katim": "Putra",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": "iveeel baik",
                "status_at": "2026-09-15T16:44:12.000000Z"
            },
            {
                "id": "01a0a477-564c-72ae-b5fe-26c9d3f0cb8a",
                "pppoe_secret": "siti@griyanet",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Pasuruan Selatan",
                "customer_name": "Siti Rahayu",
                "splitter": "1/2/1",
                "team_name": "Ikan",
                "katim": "Bima",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-15T09:47:59.000000Z"
            },
            {
                "id": "01a0a478-3f17-71df-9ad6-ca18d101591a",
                "pppoe_secret": "agus@griyanet",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Pasuruan Selatan",
                "customer_name": "Agus Prasetyo",
                "splitter": "1/2/1",
                "team_name": "Ikan",
                "katim": "Bima",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-15T09:48:59.000000Z"
            },
            {
                "id": "01a0a479-8162-72d4-b755-cce60c0eabea",
                "pppoe_secret": "hendra@griyanet",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Temas",
                "customer_name": "Hendra Wijaya",
                "splitter": "1/2/1",
                "team_name": "Tapir",
                "katim": "Putra",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-15T09:50:21.000000Z"
            },
            {
                "id": "01a0a47a-1f2b-727b-8fd4-db6d3b9be464",
                "pppoe_secret": "rina@griyanet",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Pasuruan Selatan",
                "customer_name": "Rina Marlina",
                "splitter": "1/2/1",
                "team_name": "Ikan",
                "katim": "Bima",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-15T09:51:01.000000Z"
            },
            {
                "id": "01a0a47a-d00e-7238-8172-bcf2b5765c7c",
                "pppoe_secret": "ahmad@griyanet",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Pasuruan Selatan",
                "customer_name": "Ahmad Fauzi",
                "splitter": "1/2/1",
                "team_name": "Gajah",
                "katim": "Andi",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-15T09:51:47.000000Z"
            },
            {
                "id": "01a0a47b-eac7-71ea-a4de-7b012b30e9a6",
                "pppoe_secret": "wulan@griyanet",
                "wo_date": "2026-08-05",
                "no_wo": "1",
                "branch_name": "Pasuruan Selatan",
                "customer_name": "Wulan Sari",
                "splitter": "1/2/1",
                "team_name": "Ikan",
                "katim": "Bima",
                "status": "WAITING",
                "status_name": "Menunggu Validasi",
                "score": "0.0",
                "status_by": null,
                "status_at": "2026-09-15T09:52:59.000000Z"
            }
        ],
        "first_page_url": "https://dev-api-nice.dasaria.id/api/work-order/validation?page=1",
        "from": 1,
        "last_page": 2,
        "last_page_url": "https://dev-api-nice.dasaria.id/api/work-order/validation?page=2",
        "links": [
            {
                "url": null,
                "label": "« Previous",
                "page": null,
                "active": false
            },
            {
                "url": "https://dev-api-nice.dasaria.id/api/work-order/validation?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": "https://dev-api-nice.dasaria.id/api/work-order/validation?page=2",
                "label": "2",
                "page": 2,
                "active": false
            },
            {
                "url": "https://dev-api-nice.dasaria.id/api/work-order/validation?page=2",
                "label": "Next »",
                "page": 2,
                "active": false
            }
        ],
        "next_page_url": "https://dev-api-nice.dasaria.id/api/work-order/validation?page=2",
        "path": "https://dev-api-nice.dasaria.id/api/work-order/validation",
        "per_page": 10,
        "prev_page_url": null,
        "to": 10,
        "total": 12
    }
}
JSON;

    /**
     * Response sukses untuk endpoint listStatus() — List semua status validasi WO.
     */
    public const LIST_STATUS = <<<'JSON'
{
    "meta": {
        "status": 200,
        "message": "Berhasil mengambil data validasi WO"
    },
    "data": [
        {
            "id": "01a03427-64c6-7382-aa4f-0f361e0eef6d",
            "name": "Draft",
            "code": "DRAFT",
            "is_final": 0,
            "is_active": 1,
            "created_at": "2026-08-24T14:23:12.000000Z",
            "updated_at": "2026-08-24T14:23:12.000000Z",
            "deleted_at": null
        },
        {
            "id": "01a03427-64c8-71fe-b67d-2dcd3f995f03",
            "name": "Sedang Diproses",
            "code": "IN_PROGRESS",
            "is_final": 0,
            "is_active": 1,
            "created_at": "2026-08-24T14:23:12.000000Z",
            "updated_at": "2026-08-24T14:23:12.000000Z",
            "deleted_at": null
        },
        {
            "id": "01a03427-64ca-72f3-9734-3a89cd1d3b61",
            "name": "Selesai",
            "code": "COMPLETE",
            "is_final": 1,
            "is_active": 1,
            "created_at": "2026-08-24T14:23:12.000000Z",
            "updated_at": "2026-08-24T14:23:12.000000Z",
            "deleted_at": null
        },
        {
            "id": "01a03427-64cb-73ce-a6ff-840a63edbad0",
            "name": "Menunggu Validasi",
            "code": "WAITING",
            "is_final": 0,
            "is_active": 1,
            "created_at": "2026-08-24T14:23:12.000000Z",
            "updated_at": "2026-08-24T14:23:12.000000Z",
            "deleted_at": null
        }
    ]
}
JSON;

    /**
     * Response sukses untuk endpoint show() — Detail validasi WO.
     */
    public const DETAIL_VALIDATION_WO = <<<'JSON'
    {
        "meta": {
            "status": 200,
            "message": "Berhasil mengambil detail validasi WO"
        },
        "data": {
            "status": "IN_PROGRESS",
            "no_wo": "1",
            "customer_name": "Dewi Lestari",
            "branch_name": "Temas",
            "pppoe": "dewi@griyanet",
            "katim": "Andi Pratama",
            "team": "Tim Tapir",
            "access_erp": "1\/2\/1",
            "start": "2026-08-05T08:00:00.000000Z",
            "end": "2026-08-05T10:00:00.000000Z",
            "duration": 120,
            "distance_sales": 100,
            "distance_team": 50,
            "dropcore": 30,
            "notes": "",
            "status_by": "Superadmin User",
            "status_at": "2026-09-15T11:02:16.000000Z",
            "newest": false,
            "assessments": [
                {
                    "assessment": {
                        "id": "01a05c7c-838c-706c-9b51-55db70e6ccab",
                        "name": "Instalasi Customer",
                        "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                        "created_by": null,
                        "is_active": 1,
                        "created_at": "2026-09-01T10:20:59.000000Z",
                        "updated_at": "2026-09-01T10:20:59.000000Z",
                        "deleted_at": null
                    },
                    "detail_id": "01a08ffa-db80-73a2-9e6c-cdbb24f6efaf",
                    "total_score": 150,
                    "categories": [
                        {
                            "category": {
                                "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                "name": "Kualitas",
                                "code": "KUALITAS",
                                "max_score": 100,
                                "is_active": 1,
                                "created_at": "2026-09-01T10:20:59.000000Z",
                                "updated_at": "2026-09-01T10:20:59.000000Z",
                                "deleted_at": null
                            },
                            "total_weight_active": 100,
                            "items": [
                                {
                                    "id": "01a0618a-0bf0-734c-b279-4881414c20e2",
                                    "assessment_id": "01a05c7c-838c-706c-9b51-55db70e6ccab",
                                    "assessment_category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                    "name": "Redaman OPM di ODP",
                                    "criteria_code": "PN-0002",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 30,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-02T09:53:52.000000Z",
                                    "updated_at": "2026-09-04T06:29:44.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-db84-713f-b742-bb84903fc2c0",
                                    "obtained_score": 0,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                        "name": "Kualitas",
                                        "code": "KUALITAS",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a0618a-0c1c-7234-89cf-112298c68984",
                                            "assessment_criteria_id": "01a0618a-0bf0-734c-b279-4881414c20e2",
                                            "rule_code": "PN-0002-R1",
                                            "rule": "Redaman di luar batas wajar",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-02T09:53:52.000000Z",
                                            "updated_at": "2026-09-03T10:18:04.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 1,
                                            "is_pass": 1,
                                            "detail_rule_id": "a2b83dfd-c1e0-4772-b6c2-c120b0a045b9"
                                        },
                                        {
                                            "id": "01a0618a-0c1f-73c5-8cd9-6d858cee7872",
                                            "assessment_criteria_id": "01a0618a-0bf0-734c-b279-4881414c20e2",
                                            "rule_code": "PN-0002-R2",
                                            "rule": "Foto OPM tidak jelas\/blur",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-02T09:53:52.000000Z",
                                            "updated_at": "2026-09-03T10:18:04.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 1,
                                            "is_pass": 1,
                                            "detail_rule_id": "a2b83dfd-c7a6-4025-9abc-7cc694cbcf4f"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a066c6-8f91-70c7-8950-51f09b78e90a",
                                            "assessment_criteria_id": "01a0618a-0bf0-734c-b279-4881414c20e2",
                                            "sequence": 1,
                                            "step_id": "3",
                                            "step_code": "TEK-03",
                                            "step_name": "Cek Redaman ODP via OPM",
                                            "created_at": "2026-09-03T10:18:04.000000Z",
                                            "updated_at": "2026-09-03T10:18:04.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/indo-digital.com\/wp-content\/uploads\/2014\/10\/aop05-264x300.jpg",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T08:10:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                },
                                                {
                                                    "key": "redaman_odp",
                                                    "value": "0.75",
                                                    "is_active": true,
                                                    "type_data": "number",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-c7b7-45e1-88cb-e6a6a3b030a0",
                                                    "id_step_values": "a2b83dfd-c7b7-45e1-88cb-e6a6a3b030a0",
                                                    "assessment_step_values_id": "a2b83dfd-c7b0-4fbe-bffc-3b7150249289",
                                                    "variable_name": null,
                                                    "variable_code": "redaman_odp",
                                                    "order": 0,
                                                    "type_data": "number",
                                                    "value": "0.75",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066c6-8f96-71a7-aec5-0d80c7c5b7b4",
                                                    "criteria_step_id": "01a066c6-8f91-70c7-8950-51f09b78e90a",
                                                    "variable_name": "redaman_odp",
                                                    "variable_code": "redaman_odp",
                                                    "order": 1,
                                                    "type_data": "number",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:18:04.000000Z",
                                                    "updated_at": "2026-09-03T10:18:04.000000Z",
                                                    "deleted_at": null,
                                                    "value": "0.75",
                                                    "timestamp": "2026-08-05T08:10:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "id": "01a066c7-95d2-70bc-8c2e-fa99ff725193",
                                    "assessment_id": "01a05c7c-838c-706c-9b51-55db70e6ccab",
                                    "assessment_category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                    "name": "Sambungan Drop Core\u2013Patchcord Modem",
                                    "criteria_code": "PN-0008",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 20,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T10:19:11.000000Z",
                                    "updated_at": "2026-09-04T06:29:44.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-db9a-70a0-a63b-7db4f5e0945c",
                                    "obtained_score": 0,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                        "name": "Kualitas",
                                        "code": "KUALITAS",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a066c7-95eb-7366-a886-760433160092",
                                            "assessment_criteria_id": "01a066c7-95d2-70bc-8c2e-fa99ff725193",
                                            "rule_code": "PN-0008-R1",
                                            "rule": "Nilai sambungan di luar batas wajar",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T10:19:11.000000Z",
                                            "updated_at": "2026-09-03T10:19:11.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 1,
                                            "is_pass": 1,
                                            "detail_rule_id": "a2b83dfd-c915-4165-a54e-b9b0b727397a"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a066c7-95e2-70d6-b977-08fb73839377",
                                            "assessment_criteria_id": "01a066c7-95d2-70bc-8c2e-fa99ff725193",
                                            "sequence": 1,
                                            "step_id": "7",
                                            "step_code": "TEK-07",
                                            "step_name": "Nilai Sambungan DC ke Patchcord Modem",
                                            "created_at": "2026-09-03T10:19:11.000000Z",
                                            "updated_at": "2026-09-03T10:19:11.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/fibconet.com\/wp-content\/uploads\/2023\/07\/otdr-1.jpg",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T08:50:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T08:50:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T08:50:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-c92b-4d2a-9da3-4760e78403b3",
                                                    "id_step_values": "a2b83dfd-c92b-4d2a-9da3-4760e78403b3",
                                                    "assessment_step_values_id": "a2b83dfd-c923-481c-8359-10ceabc97067",
                                                    "variable_name": null,
                                                    "variable_code": "input_nilai_sambungan_db",
                                                    "order": 0,
                                                    "type_data": "number",
                                                    "value": "0.20",
                                                    "is_default": false,
                                                    "timestamp": "2026-08-05T08:50:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066c7-95e6-702a-acc7-d6174a127a2b",
                                                    "criteria_step_id": "01a066c7-95e2-70d6-b977-08fb73839377",
                                                    "variable_name": "input_nilai_sambungan_db",
                                                    "variable_code": "input_nilai_sambungan_db",
                                                    "order": 1,
                                                    "type_data": "number",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:19:11.000000Z",
                                                    "updated_at": "2026-09-03T10:19:11.000000Z",
                                                    "deleted_at": null,
                                                    "value": "0.20",
                                                    "timestamp": "2026-08-05T08:50:00+07:00",
                                                    "is_default": false
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "id": "01a066c9-7174-7169-bf9a-a21536c208ba",
                                    "assessment_id": "01a05c7c-838c-706c-9b51-55db70e6ccab",
                                    "assessment_category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                    "name": "Hasil Speed Test",
                                    "criteria_code": "PN-0009",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 50,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T10:21:12.000000Z",
                                    "updated_at": "2026-09-04T06:29:44.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-db9e-73ce-9b38-f855fec2ea2e",
                                    "obtained_score": 50,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                        "name": "Kualitas",
                                        "code": "KUALITAS",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a066c9-718d-73ab-9295-f822696c74d3",
                                            "assessment_criteria_id": "01a066c9-7174-7169-bf9a-a21536c208ba",
                                            "rule_code": "PN-0009-R1",
                                            "rule": "Hasil speed test di bawah paket",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T10:21:12.000000Z",
                                            "updated_at": "2026-09-03T10:21:36.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-ca38-4098-964a-10d4b3ffd93b"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a066c9-ccf6-705f-8df6-d949a6ac6d64",
                                            "assessment_criteria_id": "01a066c9-7174-7169-bf9a-a21536c208ba",
                                            "sequence": 1,
                                            "step_id": "11",
                                            "step_code": "TEK-11",
                                            "step_name": "Upload Foto Speed Test",
                                            "created_at": "2026-09-03T10:21:36.000000Z",
                                            "updated_at": "2026-09-03T10:21:36.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T09:40:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T09:40:00+07:00"
                                                },
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/i.pcmag.com\/imagery\/articles\/03xdeDG4m4n0gJG3CbFNIgm-17..v1661444591.png",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T09:40:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-ca4b-4c76-b1bb-60e94c8ba665",
                                                    "id_step_values": "a2b83dfd-ca4b-4c76-b1bb-60e94c8ba665",
                                                    "assessment_step_values_id": "a2b83dfd-ca44-4561-bed5-88bf96bc4217",
                                                    "variable_name": null,
                                                    "variable_code": "foto",
                                                    "order": 0,
                                                    "type_data": "image",
                                                    "value": "https:\/\/i.pcmag.com\/imagery\/articles\/03xdeDG4m4n0gJG3CbFNIgm-17..v1661444591.png",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T09:40:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066c9-ccfb-73f7-887d-f54a8222af46",
                                                    "criteria_step_id": "01a066c9-ccf6-705f-8df6-d949a6ac6d64",
                                                    "variable_name": "foto",
                                                    "variable_code": "foto",
                                                    "order": 1,
                                                    "type_data": "image",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:21:36.000000Z",
                                                    "updated_at": "2026-09-03T10:21:36.000000Z",
                                                    "deleted_at": null,
                                                    "value": "https:\/\/i.pcmag.com\/imagery\/articles\/03xdeDG4m4n0gJG3CbFNIgm-17..v1661444591.png",
                                                    "timestamp": "2026-08-05T09:40:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "category": {
                                "id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                "name": "Kerapian",
                                "code": "KERAPIAN",
                                "max_score": 100,
                                "is_active": 1,
                                "created_at": "2026-09-01T10:20:59.000000Z",
                                "updated_at": "2026-09-01T10:20:59.000000Z",
                                "deleted_at": null
                            },
                            "total_weight_active": 100,
                            "items": [
                                {
                                    "id": "01a066cb-1f7a-705e-8dae-1ce64eebc81a",
                                    "assessment_id": "01a05c7c-838c-706c-9b51-55db70e6ccab",
                                    "assessment_category_id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                    "name": "Peletakan & Instalasi Modem",
                                    "criteria_code": "PN-0010",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 100,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T10:23:03.000000Z",
                                    "updated_at": "2026-09-04T06:30:01.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-dba0-7077-9125-a620e5d02711",
                                    "obtained_score": 100,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                        "name": "Kerapian",
                                        "code": "KERAPIAN",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a066cb-1f95-720b-b6e3-7928dff7f45c",
                                            "assessment_criteria_id": "01a066cb-1f7a-705e-8dae-1ce64eebc81a",
                                            "rule_code": "PN-0010-R1",
                                            "rule": "Modem tidak menempel rapi",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T10:23:03.000000Z",
                                            "updated_at": "2026-09-03T10:23:24.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-cb86-4ee0-96a0-914f8b3f6184"
                                        },
                                        {
                                            "id": "01a066cb-1f99-7199-aaaa-3867df184a17",
                                            "assessment_criteria_id": "01a066cb-1f7a-705e-8dae-1ce64eebc81a",
                                            "rule_code": "PN-0010-R2",
                                            "rule": "Kabel power tidak tertata",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T10:23:03.000000Z",
                                            "updated_at": "2026-09-03T10:23:24.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-cb93-4cfd-bd02-31ffa82f5e7c"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a066cb-753f-71f0-8938-ccd134a70307",
                                            "assessment_criteria_id": "01a066cb-1f7a-705e-8dae-1ce64eebc81a",
                                            "sequence": 1,
                                            "step_id": "10",
                                            "step_code": "TEK-10",
                                            "step_name": "Upload Foto Instalasi Modem",
                                            "created_at": "2026-09-03T10:23:24.000000Z",
                                            "updated_at": "2026-09-03T10:23:24.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T09:25:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T09:25:00+07:00"
                                                },
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/asset.kompas.com\/crops\/HvZyI6sl3BIuDnvZmM4U3rATMig=\/0x0:1000x667\/1200x800\/data\/photo\/2022\/06\/27\/62b8a1de32dea.jpg",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T09:25:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-cba5-4803-a579-9369870d37eb",
                                                    "id_step_values": "a2b83dfd-cba5-4803-a579-9369870d37eb",
                                                    "assessment_step_values_id": "a2b83dfd-cb9d-4160-ad68-8703f2981772",
                                                    "variable_name": null,
                                                    "variable_code": "foto",
                                                    "order": 0,
                                                    "type_data": "image",
                                                    "value": "https:\/\/asset.kompas.com\/crops\/HvZyI6sl3BIuDnvZmM4U3rATMig=\/0x0:1000x667\/1200x800\/data\/photo\/2022\/06\/27\/62b8a1de32dea.jpg",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T09:25:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066cb-7542-7047-b129-2362a7fc82c0",
                                                    "criteria_step_id": "01a066cb-753f-71f0-8938-ccd134a70307",
                                                    "variable_name": "foto",
                                                    "variable_code": "foto",
                                                    "order": 1,
                                                    "type_data": "image",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:23:24.000000Z",
                                                    "updated_at": "2026-09-03T10:23:24.000000Z",
                                                    "deleted_at": null,
                                                    "value": "https:\/\/asset.kompas.com\/crops\/HvZyI6sl3BIuDnvZmM4U3rATMig=\/0x0:1000x667\/1200x800\/data\/photo\/2022\/06\/27\/62b8a1de32dea.jpg",
                                                    "timestamp": "2026-08-05T09:25:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    "assessment": {
                        "id": "01a05c7c-8389-721d-b6f5-0dd692f94df9",
                        "name": "Instalasi ODP",
                        "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                        "created_by": null,
                        "is_active": 1,
                        "created_at": "2026-09-01T10:20:59.000000Z",
                        "updated_at": "2026-09-01T10:20:59.000000Z",
                        "deleted_at": null
                    },
                    "detail_id": "01a08ffa-dba3-716e-8e4e-d1c9abfe0f51",
                    "total_score": 200,
                    "categories": [
                        {
                            "category": {
                                "id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                "name": "Kerapian",
                                "code": "KERAPIAN",
                                "max_score": 100,
                                "is_active": 1,
                                "created_at": "2026-09-01T10:20:59.000000Z",
                                "updated_at": "2026-09-01T10:20:59.000000Z",
                                "deleted_at": null
                            },
                            "total_weight_active": 100,
                            "items": [
                                {
                                    "id": "01a06184-c7ff-7154-9cf0-dbdd9b47ffb6",
                                    "assessment_id": "01a05c7c-8389-721d-b6f5-0dd692f94df9",
                                    "assessment_category_id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                    "name": "Penataan Patchcord ODP",
                                    "criteria_code": "PN-0001",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 50,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-02T09:48:07.000000Z",
                                    "updated_at": "2026-09-03T09:10:24.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-dba5-736d-b218-212a04008af9",
                                    "obtained_score": 50,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                        "name": "Kerapian",
                                        "code": "KERAPIAN",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a06184-c87d-7263-bb8f-115495258f18",
                                            "assessment_criteria_id": "01a06184-c7ff-7154-9cf0-dbdd9b47ffb6",
                                            "rule_code": "PN-0001-R1",
                                            "rule": "Patchcord tidak tertata rapi",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-02T09:48:07.000000Z",
                                            "updated_at": "2026-09-03T08:49:50.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-cd36-495d-a70d-231d1f4d57dc"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a06675-cb6c-70ad-838b-cac4bf576a03",
                                            "assessment_criteria_id": "01a06184-c7ff-7154-9cf0-dbdd9b47ffb6",
                                            "sequence": 1,
                                            "step_id": "5",
                                            "step_code": "TEK-05",
                                            "step_name": "Instalasi Patchcord ODP Sebelum Ditutup",
                                            "created_at": "2026-09-03T08:49:50.000000Z",
                                            "updated_at": "2026-09-03T08:49:50.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcS7i-uwfravbCFZS4ylYchlcFSVWWTDETyeY6P3qlDlqg&s=10",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T08:30:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T08:30:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T08:30:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-cd49-4834-8aa1-0ddfaa605e6c",
                                                    "id_step_values": "a2b83dfd-cd49-4834-8aa1-0ddfaa605e6c",
                                                    "assessment_step_values_id": "a2b83dfd-cd41-4cdd-9c5e-c8984e396089",
                                                    "variable_name": null,
                                                    "variable_code": "foto",
                                                    "order": 0,
                                                    "type_data": "image",
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcS7i-uwfravbCFZS4ylYchlcFSVWWTDETyeY6P3qlDlqg&s=10",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T08:30:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a06675-cb6f-7336-ab3a-a099b8e0df59",
                                                    "criteria_step_id": "01a06675-cb6c-70ad-838b-cac4bf576a03",
                                                    "variable_name": "foto",
                                                    "variable_code": "foto",
                                                    "order": 1,
                                                    "type_data": "image",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T08:49:50.000000Z",
                                                    "updated_at": "2026-09-03T08:49:50.000000Z",
                                                    "deleted_at": null,
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcS7i-uwfravbCFZS4ylYchlcFSVWWTDETyeY6P3qlDlqg&s=10",
                                                    "timestamp": "2026-08-05T08:30:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "id": "01a06676-e1c2-72b6-89c2-e19c29973464",
                                    "assessment_id": "01a05c7c-8389-721d-b6f5-0dd692f94df9",
                                    "assessment_category_id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                    "name": "Tutup ODP",
                                    "criteria_code": "PN-0005",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 50,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T08:51:02.000000Z",
                                    "updated_at": "2026-09-03T09:10:24.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-dba8-704e-a268-46a8103c434d",
                                    "obtained_score": 50,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                                        "name": "Kerapian",
                                        "code": "KERAPIAN",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a06676-e1db-7316-a006-e479ea07f8c7",
                                            "assessment_criteria_id": "01a06676-e1c2-72b6-89c2-e19c29973464",
                                            "rule_code": "PN-0005-R1",
                                            "rule": "Tidak ada tutup ODP",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T08:51:02.000000Z",
                                            "updated_at": "2026-09-03T08:51:02.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-cdf5-4306-9a0f-2d40ca173731"
                                        },
                                        {
                                            "id": "01a06676-e1df-7111-bc9f-762170bcf62c",
                                            "assessment_criteria_id": "01a06676-e1c2-72b6-89c2-e19c29973464",
                                            "rule_code": "PN-0005-R2",
                                            "rule": "ODP pecah",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T08:51:02.000000Z",
                                            "updated_at": "2026-09-03T08:51:02.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-cdfe-4404-9013-1532c074cd62"
                                        },
                                        {
                                            "id": "01a06676-e1e3-7132-93b8-a6db1234a325",
                                            "assessment_criteria_id": "01a06676-e1c2-72b6-89c2-e19c29973464",
                                            "rule_code": "PN-0005-R3",
                                            "rule": "Kunci belakang pecah",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T08:51:02.000000Z",
                                            "updated_at": "2026-09-03T08:51:02.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-ce05-4e2e-b673-d0bd801cff80"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a06676-e1cf-7172-a9d3-8be8dae5de63",
                                            "assessment_criteria_id": "01a06676-e1c2-72b6-89c2-e19c29973464",
                                            "sequence": 1,
                                            "step_id": "6",
                                            "step_code": "TEK-06",
                                            "step_name": "Tutup ODP",
                                            "created_at": "2026-09-03T08:51:02.000000Z",
                                            "updated_at": "2026-09-03T08:51:02.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSdstOtBTI492qW-eBtQ87Ww222FZQjLZTMDU41i4V7C1t6JZqrt3yGqrlM&s=10",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T08:40:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T08:40:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T08:40:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-ce11-4af3-b531-36ea21d71be2",
                                                    "id_step_values": "a2b83dfd-ce11-4af3-b531-36ea21d71be2",
                                                    "assessment_step_values_id": "a2b83dfd-ce0b-48b9-b3b9-b03cbfe4f0e5",
                                                    "variable_name": null,
                                                    "variable_code": "foto",
                                                    "order": 0,
                                                    "type_data": "image",
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSdstOtBTI492qW-eBtQ87Ww222FZQjLZTMDU41i4V7C1t6JZqrt3yGqrlM&s=10",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T08:40:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a06676-e1d4-7333-8ff1-9630c577ef77",
                                                    "criteria_step_id": "01a06676-e1cf-7172-a9d3-8be8dae5de63",
                                                    "variable_name": "foto",
                                                    "variable_code": "foto",
                                                    "order": 1,
                                                    "type_data": "image",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T08:51:02.000000Z",
                                                    "updated_at": "2026-09-03T08:51:02.000000Z",
                                                    "deleted_at": null,
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSdstOtBTI492qW-eBtQ87Ww222FZQjLZTMDU41i4V7C1t6JZqrt3yGqrlM&s=10",
                                                    "timestamp": "2026-08-05T08:40:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "category": {
                                "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                "name": "Kualitas",
                                "code": "KUALITAS",
                                "max_score": 100,
                                "is_active": 1,
                                "created_at": "2026-09-01T10:20:59.000000Z",
                                "updated_at": "2026-09-01T10:20:59.000000Z",
                                "deleted_at": null
                            },
                            "total_weight_active": 100,
                            "items": [
                                {
                                    "id": "01a06673-e10e-72e7-b24d-7dff2f6d6744",
                                    "assessment_id": "01a05c7c-8389-721d-b6f5-0dd692f94df9",
                                    "assessment_category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                    "name": "Sambungan Patchcord ODP\u2013Drop Core",
                                    "criteria_code": "PN-0003",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 70,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T08:47:45.000000Z",
                                    "updated_at": "2026-09-03T09:11:10.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-dbaa-73a1-8822-3076c29a46bb",
                                    "obtained_score": 70,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                        "name": "Kualitas",
                                        "code": "KUALITAS",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a06673-e13e-73cb-93a1-2422583a8eba",
                                            "assessment_criteria_id": "01a06673-e10e-72e7-b24d-7dff2f6d6744",
                                            "rule_code": "PN-0003-R1",
                                            "rule": "Nilai sambungan di luar batas wajar",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T08:47:45.000000Z",
                                            "updated_at": "2026-09-03T08:47:45.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-ceca-4920-906d-6c9ccb94636a"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a06673-e132-7067-9d64-f855becfd6e8",
                                            "assessment_criteria_id": "01a06673-e10e-72e7-b24d-7dff2f6d6744",
                                            "sequence": 1,
                                            "step_id": "4",
                                            "step_code": "TEK-04",
                                            "step_name": "Nilai Sambungan Patchcord ke Drop Core",
                                            "created_at": "2026-09-03T08:47:45.000000Z",
                                            "updated_at": "2026-09-03T08:47:45.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcQGmgxgdAOeIiCQp-3RhnXuuVAYj4qMo02UhLTwPWrN4k3l0Ox8gBa7rUE&s=10",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T08:20:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T08:20:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T08:20:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-cee3-4e23-a019-26156ca365d2",
                                                    "id_step_values": "a2b83dfd-cee3-4e23-a019-26156ca365d2",
                                                    "assessment_step_values_id": "a2b83dfd-ced9-4045-b116-220b27acd7a7",
                                                    "variable_name": null,
                                                    "variable_code": "input_nilai_sambungan_db",
                                                    "order": 0,
                                                    "type_data": "number",
                                                    "value": "0.25",
                                                    "is_default": false,
                                                    "timestamp": "2026-08-05T08:20:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a06673-e137-73d2-92f0-8b8a925b43c6",
                                                    "criteria_step_id": "01a06673-e132-7067-9d64-f855becfd6e8",
                                                    "variable_name": "input_nilai_sambungan_db",
                                                    "variable_code": "input_nilai_sambungan_db",
                                                    "order": 1,
                                                    "type_data": "number",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T08:47:45.000000Z",
                                                    "updated_at": "2026-09-03T08:47:45.000000Z",
                                                    "deleted_at": null,
                                                    "value": "0.25",
                                                    "timestamp": "2026-08-05T08:20:00+07:00",
                                                    "is_default": false
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "id": "01a06674-ffbb-73df-bd4d-040b1a164c34",
                                    "assessment_id": "01a05c7c-8389-721d-b6f5-0dd692f94df9",
                                    "assessment_category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                    "name": "Redaman OPM di ODP",
                                    "criteria_code": "PN-0004",
                                    "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                    "operation_id": null,
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 30,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T08:48:58.000000Z",
                                    "updated_at": "2026-09-03T09:11:10.000000Z",
                                    "deleted_at": null,
                                    "operation_name": null,
                                    "operation_code": null,
                                    "operation_symbol": null,
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Single Source",
                                    "criteria_type_code": "SINGLE",
                                    "detail_criteria_id": "01a08ffa-dbad-7008-b816-59a26d7616c6",
                                    "obtained_score": 30,
                                    "calculated_value": null,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                        "name": "Kualitas",
                                        "code": "KUALITAS",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": null,
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-8356-727d-b363-abf57bca2551",
                                        "code": "SINGLE",
                                        "name": "Single Source",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a06674-ffd8-72e5-894a-59e1c70ccbe8",
                                            "assessment_criteria_id": "01a06674-ffbb-73df-bd4d-040b1a164c34",
                                            "rule_code": "PN-0004-R1",
                                            "rule": "Redaman di luar batas wajar",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T08:48:58.000000Z",
                                            "updated_at": "2026-09-03T08:48:58.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-d25e-4576-aa3a-1252542b463a"
                                        },
                                        {
                                            "id": "01a06674-ffdc-731a-a0c0-755ba0859268",
                                            "assessment_criteria_id": "01a06674-ffbb-73df-bd4d-040b1a164c34",
                                            "rule_code": "PN-0004-R2",
                                            "rule": "Foto OPM tidak jelas\/blur",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T08:48:58.000000Z",
                                            "updated_at": "2026-09-03T08:48:58.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-d26a-47f2-89e3-f32f60d6aab8"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a06674-ffcc-70b1-9d0e-6446f200a185",
                                            "assessment_criteria_id": "01a06674-ffbb-73df-bd4d-040b1a164c34",
                                            "sequence": 1,
                                            "step_id": "3",
                                            "step_code": "TEK-03",
                                            "step_name": "Redaman ODP pada OPM",
                                            "created_at": "2026-09-03T08:48:58.000000Z",
                                            "updated_at": "2026-09-03T08:48:58.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/indo-digital.com\/wp-content\/uploads\/2014\/10\/aop05-264x300.jpg",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T08:10:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                },
                                                {
                                                    "key": "redaman_odp",
                                                    "value": "0.75",
                                                    "is_active": true,
                                                    "type_data": "number",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-d27a-438d-b4cf-033a8fe95179",
                                                    "id_step_values": "a2b83dfd-d27a-438d-b4cf-033a8fe95179",
                                                    "assessment_step_values_id": "a2b83dfd-d272-4ab1-be22-7eb8abc3f1e5",
                                                    "variable_name": null,
                                                    "variable_code": "redaman_odp",
                                                    "order": 0,
                                                    "type_data": "number",
                                                    "value": "0.75",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a06674-ffd1-73fe-a4f2-f973ae835f5e",
                                                    "criteria_step_id": "01a06674-ffcc-70b1-9d0e-6446f200a185",
                                                    "variable_name": "redaman_odp",
                                                    "variable_code": "redaman_odp",
                                                    "order": 1,
                                                    "type_data": "number",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T08:48:58.000000Z",
                                                    "updated_at": "2026-09-03T08:48:58.000000Z",
                                                    "deleted_at": null,
                                                    "value": "0.75",
                                                    "timestamp": "2026-08-05T08:10:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    "assessment": {
                        "id": "01a05c7c-838f-73f8-a83c-e907a0caa285",
                        "name": "Penarikan",
                        "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                        "created_by": null,
                        "is_active": 1,
                        "created_at": "2026-09-01T10:20:59.000000Z",
                        "updated_at": "2026-09-01T10:20:59.000000Z",
                        "deleted_at": null
                    },
                    "detail_id": "01a08ffa-dbb5-715e-9081-cfaf7f038c5a",
                    "total_score": 100,
                    "categories": [
                        {
                            "category": {
                                "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                "name": "Kualitas",
                                "code": "KUALITAS",
                                "max_score": 100,
                                "is_active": 1,
                                "created_at": "2026-09-01T10:20:59.000000Z",
                                "updated_at": "2026-09-01T10:20:59.000000Z",
                                "deleted_at": null
                            },
                            "total_weight_active": 100,
                            "items": [
                                {
                                    "id": "01a066c4-ec3b-7330-9b64-1a726481d963",
                                    "assessment_id": "01a05c7c-838f-73f8-a83c-e907a0caa285",
                                    "assessment_category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                    "name": "Selisih Redaman Customer \u2212 ODP",
                                    "criteria_code": "PN-0006",
                                    "criteria_type_id": "01a05c7c-835a-72ce-89f1-bae92944587f",
                                    "operation_id": "01a05c7c-8368-71f8-b650-d2dbb95f0331",
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 50,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T10:16:16.000000Z",
                                    "updated_at": "2026-09-04T06:30:13.000000Z",
                                    "deleted_at": null,
                                    "operation_name": "Pengurangan",
                                    "operation_code": "SUBTRACTION",
                                    "operation_symbol": "-",
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Kalkulasi",
                                    "criteria_type_code": "CALCULATE",
                                    "detail_criteria_id": "01a08ffa-dbb7-70e3-88a7-fc358a0a9ec7",
                                    "obtained_score": 50,
                                    "calculated_value": 0.1,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                        "name": "Kualitas",
                                        "code": "KUALITAS",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": {
                                        "id": "01a05c7c-8368-71f8-b650-d2dbb95f0331",
                                        "code": "SUBTRACTION",
                                        "symbol": "-",
                                        "name": "Pengurangan",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-835a-72ce-89f1-bae92944587f",
                                        "code": "CALCULATE",
                                        "name": "Kalkulasi",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a066c4-ec72-7245-b82b-f36be69ee76a",
                                            "assessment_criteria_id": "01a066c4-ec3b-7330-9b64-1a726481d963",
                                            "rule_code": "PN-0006-R1",
                                            "rule": "Selisih redaman tidak wajar",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T10:16:16.000000Z",
                                            "updated_at": "2026-09-03T10:16:16.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-d3cd-4351-b4cd-08db53547463"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a066c4-ec5f-72bc-a6d1-83ce1e2d4092",
                                            "assessment_criteria_id": "01a066c4-ec3b-7330-9b64-1a726481d963",
                                            "sequence": 1,
                                            "step_id": "8",
                                            "step_code": "TEK-08",
                                            "step_name": "Redaman Pelanggan via OPM",
                                            "created_at": "2026-09-03T10:16:16.000000Z",
                                            "updated_at": "2026-09-03T10:16:16.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSbTTU5gw5VmMNTO-NRBkRoeUPwkYS9H31EtYbM213BBQ&s=10",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T09:00:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T09:00:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T09:00:00+07:00"
                                                },
                                                {
                                                    "key": "redaman_pelanggan",
                                                    "value": "0.85",
                                                    "is_active": true,
                                                    "type_data": "number",
                                                    "timestamp": "2026-08-05T09:00:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-d3df-4ed8-b91f-e35ea9cf1fe5",
                                                    "id_step_values": "a2b83dfd-d3df-4ed8-b91f-e35ea9cf1fe5",
                                                    "assessment_step_values_id": "a2b83dfd-d3d8-4e63-a74f-a8e90596584d",
                                                    "variable_name": null,
                                                    "variable_code": "redaman_pelanggan",
                                                    "order": 0,
                                                    "type_data": "number",
                                                    "value": "0.85",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T09:00:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066c4-ec63-73f3-952d-d5a1a511a375",
                                                    "criteria_step_id": "01a066c4-ec5f-72bc-a6d1-83ce1e2d4092",
                                                    "variable_name": "redaman_pelanggan",
                                                    "variable_code": "redaman_pelanggan",
                                                    "order": 1,
                                                    "type_data": "number",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:16:16.000000Z",
                                                    "updated_at": "2026-09-03T10:16:16.000000Z",
                                                    "deleted_at": null,
                                                    "value": "0.85",
                                                    "timestamp": "2026-08-05T09:00:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        },
                                        {
                                            "id": "01a066c4-ec65-722c-b436-60df27b9c45a",
                                            "assessment_criteria_id": "01a066c4-ec3b-7330-9b64-1a726481d963",
                                            "sequence": 2,
                                            "step_id": "3",
                                            "step_code": "TEK-03",
                                            "step_name": "Cek Redaman ODP via OPM",
                                            "created_at": "2026-09-03T10:16:16.000000Z",
                                            "updated_at": "2026-09-03T10:16:16.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "foto",
                                                    "value": "https:\/\/indo-digital.com\/wp-content\/uploads\/2014\/10\/aop05-264x300.jpg",
                                                    "is_active": true,
                                                    "type_data": "image",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                },
                                                {
                                                    "key": "timestamp",
                                                    "value": "2026-08-05T08:10:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                },
                                                {
                                                    "key": "redaman_odp",
                                                    "value": "0.75",
                                                    "is_active": true,
                                                    "type_data": "number",
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-d3ee-4df2-b168-9100603af5ac",
                                                    "id_step_values": "a2b83dfd-d3ee-4df2-b168-9100603af5ac",
                                                    "assessment_step_values_id": "a2b83dfd-d3e7-4221-af7a-fe142b12a514",
                                                    "variable_name": null,
                                                    "variable_code": "redaman_odp",
                                                    "order": 0,
                                                    "type_data": "number",
                                                    "value": "0.75",
                                                    "is_default": true,
                                                    "timestamp": "2026-08-05T08:10:00+07:00"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066c4-ec69-73d8-9086-3758542eacbb",
                                                    "criteria_step_id": "01a066c4-ec65-722c-b436-60df27b9c45a",
                                                    "variable_name": "redaman_odp",
                                                    "variable_code": "redaman_odp",
                                                    "order": 1,
                                                    "type_data": "number",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:16:16.000000Z",
                                                    "updated_at": "2026-09-03T10:16:16.000000Z",
                                                    "deleted_at": null,
                                                    "value": "0.75",
                                                    "timestamp": "2026-08-05T08:10:00+07:00",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "id": "01a066c5-aa50-72b6-9638-fc7b5eb6e24d",
                                    "assessment_id": "01a05c7c-838f-73f8-a83c-e907a0caa285",
                                    "assessment_category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                    "name": "Durasi Penarikan",
                                    "criteria_code": "PN-0007",
                                    "criteria_type_id": "01a05c7c-835a-72ce-89f1-bae92944587f",
                                    "operation_id": "01a05c7c-8368-71f8-b650-d2dbb95f0331",
                                    "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                    "score": 50,
                                    "is_active": 1,
                                    "created_by": null,
                                    "created_at": "2026-09-03T10:17:05.000000Z",
                                    "updated_at": "2026-09-04T06:30:13.000000Z",
                                    "deleted_at": null,
                                    "operation_name": "Pengurangan",
                                    "operation_code": "SUBTRACTION",
                                    "operation_symbol": "-",
                                    "score_name": "Persentase",
                                    "score_code": "PERCENT",
                                    "score_symbol": "%",
                                    "criteria_type_name": "Kalkulasi",
                                    "criteria_type_code": "CALCULATE",
                                    "detail_criteria_id": "01a08ffa-dbb9-71b4-aa44-d8396c40cd00",
                                    "obtained_score": 50,
                                    "calculated_value": 2,
                                    "description": null,
                                    "category": {
                                        "id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                                        "name": "Kualitas",
                                        "code": "KUALITAS",
                                        "max_score": 100,
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "operation": {
                                        "id": "01a05c7c-8368-71f8-b650-d2dbb95f0331",
                                        "code": "SUBTRACTION",
                                        "symbol": "-",
                                        "name": "Pengurangan",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "score_type": {
                                        "id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                                        "code": "PERCENT",
                                        "name": "Persentase",
                                        "symbol": "%",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "criteria_type": {
                                        "id": "01a05c7c-835a-72ce-89f1-bae92944587f",
                                        "code": "CALCULATE",
                                        "name": "Kalkulasi",
                                        "is_active": 1,
                                        "created_at": "2026-09-01T10:20:59.000000Z",
                                        "updated_at": "2026-09-01T10:20:59.000000Z",
                                        "deleted_at": null
                                    },
                                    "rules": [
                                        {
                                            "id": "01a066c5-aa77-7183-92f9-3586b1543951",
                                            "assessment_criteria_id": "01a066c5-aa50-72b6-9638-fc7b5eb6e24d",
                                            "rule_code": "PN-0007-R1",
                                            "rule": "Durasi pengerjaan tidak wajar",
                                            "is_active": 1,
                                            "created_by": null,
                                            "created_at": "2026-09-03T10:17:05.000000Z",
                                            "updated_at": "2026-09-03T10:17:05.000000Z",
                                            "deleted_at": null,
                                            "actual_value": 0,
                                            "is_pass": 0,
                                            "detail_rule_id": "a2b83dfd-d48f-490e-a605-61f61dda4826"
                                        }
                                    ],
                                    "steps": [
                                        {
                                            "id": "01a066c5-aa5f-738f-a542-a643efc23788",
                                            "assessment_criteria_id": "01a066c5-aa50-72b6-9638-fc7b5eb6e24d",
                                            "sequence": 1,
                                            "step_id": "13",
                                            "step_code": "TEK-13",
                                            "step_name": "Selesaikan PSB",
                                            "created_at": "2026-09-03T10:17:05.000000Z",
                                            "updated_at": "2026-09-03T10:17:05.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "status_customer_active",
                                                    "value": "Active",
                                                    "is_active": true,
                                                    "type_data": "text",
                                                    "timestamp": "2026-09-10T12:00:12.000000Z"
                                                },
                                                {
                                                    "key": "waktu_selesai",
                                                    "value": "2026-08-05T10:00:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-09-10T12:00:12.000000Z"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-d4a7-4e92-afff-8a5ea08c679d",
                                                    "id_step_values": "a2b83dfd-d4a7-4e92-afff-8a5ea08c679d",
                                                    "assessment_step_values_id": "a2b83dfd-d49c-4637-8f67-b0e1610a750d",
                                                    "variable_name": null,
                                                    "variable_code": "waktu_selesai",
                                                    "order": 0,
                                                    "type_data": "timestamp",
                                                    "value": "2026-08-05T10:00:00+07:00",
                                                    "is_default": true,
                                                    "timestamp": "2026-09-10T12:00:12.000000Z"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066c5-aa65-7328-aaa9-fb8ee7655118",
                                                    "criteria_step_id": "01a066c5-aa5f-738f-a542-a643efc23788",
                                                    "variable_name": "waktu_selesai",
                                                    "variable_code": "waktu_selesai",
                                                    "order": 1,
                                                    "type_data": "timestamp",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:17:05.000000Z",
                                                    "updated_at": "2026-09-03T10:17:05.000000Z",
                                                    "deleted_at": null,
                                                    "value": "2026-08-05T10:00:00+07:00",
                                                    "timestamp": "2026-09-10T12:00:12.000000Z",
                                                    "is_default": true
                                                }
                                            ]
                                        },
                                        {
                                            "id": "01a066c5-aa66-701f-9f12-3ec8b96857ea",
                                            "assessment_criteria_id": "01a066c5-aa50-72b6-9638-fc7b5eb6e24d",
                                            "sequence": 2,
                                            "step_id": "1",
                                            "step_code": "TEK-01",
                                            "step_name": "Mulai Penarikan Kabel",
                                            "created_at": "2026-09-03T10:17:05.000000Z",
                                            "updated_at": "2026-09-03T10:17:05.000000Z",
                                            "deleted_at": null,
                                            "step_default_values": [
                                                {
                                                    "key": "waktu_mulai",
                                                    "value": "2026-08-05T08:00:00+07:00",
                                                    "is_active": true,
                                                    "type_data": "timestamp",
                                                    "timestamp": "2026-09-10T12:00:12.000000Z"
                                                }
                                            ],
                                            "step_value_assessments": [
                                                {
                                                    "id": "a2b83dfd-d4b4-4eab-8736-00b05c2ec6ca",
                                                    "id_step_values": "a2b83dfd-d4b4-4eab-8736-00b05c2ec6ca",
                                                    "assessment_step_values_id": "a2b83dfd-d4ae-40a3-993d-bc532245ff91",
                                                    "variable_name": null,
                                                    "variable_code": "waktu_mulai",
                                                    "order": 0,
                                                    "type_data": "timestamp",
                                                    "value": "2026-08-05T08:00:00+07:00",
                                                    "is_default": true,
                                                    "timestamp": "2026-09-10T12:00:12.000000Z"
                                                }
                                            ],
                                            "step_values": [
                                                {
                                                    "id": "01a066c5-aa71-70a7-92d0-2228a7c7d8b1",
                                                    "criteria_step_id": "01a066c5-aa66-701f-9f12-3ec8b96857ea",
                                                    "variable_name": "waktu_mulai",
                                                    "variable_code": "waktu_mulai",
                                                    "order": 1,
                                                    "type_data": "timestamp",
                                                    "is_active": 1,
                                                    "created_at": "2026-09-03T10:17:05.000000Z",
                                                    "updated_at": "2026-09-03T10:17:05.000000Z",
                                                    "deleted_at": null,
                                                    "value": "2026-08-05T08:00:00+07:00",
                                                    "timestamp": "2026-09-10T12:00:12.000000Z",
                                                    "is_default": true
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    }
    JSON;

    /**
     * Response sukses untuk endpoint store() — Simpan data validasi WO.
     */
    public const STORE_VALIDATION_WO = <<<'JSON'
    {
    "meta": {
        "status": 200,
        "message": "Berhasil menyimpan data validasi WO"
    },
    "data": {
        "success": true,
        "message": "Work Order berhasil disimpan",
        "data": {
        "transaction": {
            "pppoe_secret": "nuraini@griyanet",
            "assessment_code_id": "01a03427-64b3-700f-ba58-978a711f9c8d",
            "work_order_id": "1",
            "current_pic_id": null,
            "started_at": "2026-08-05T08:00:00.000000Z",
            "completed_at": "2026-08-05T10:00:00.000000Z",
            "status_id": "01a03427-64cb-73ce-a6ff-840a63edbad0",
            "result_id": null,
            "is_had_draft": false,
            "id": "01a0a47d-8987-712e-bfae-b18b08ef79a7",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
        },
        "assessment": {
            "assessment_transaction_id": "01a0a47d-8987-712e-bfae-b18b08ef79a7",
            "branch_id": "1",
            "branch_name": "Temas",
            "regional_name": "Jawa Timur",
            "pppoe_secret": "nuraini@griyanet",
            "wo_id": "1",
            "no_wo": "1",
            "customer_name": "Nur Aini",
            "assessment_code_id": "01a03427-64b3-700f-ba58-978a711f9c8d",
            "work_order_date": "2026-08-05T00:00:00.000000Z",
            "start_work_order": "2026-08-05T08:00:00.000000Z",
            "end_work_order": "2026-08-05T10:00:00.000000Z",
            "duration_minute": 120,
            "address": "Jl. Bougenville No. 11",
            "cust_extra": [],
            "erp_extra": {
            "assessment_extra": "PSB extra",
            "customer": {
                "customer_name": "Nur Aini",
                "address": "Jl. Bougenville No. 11",
                "pppoe_secret": "nuraini@griyanet",
                "branch_id": "1",
                "branch_name": "Temas",
                "regional_name": "Jawa Timur"
            },
            "technical_data": {
                "area_id": "Cluster Kenanga ID",
                "area_name": "Cluster Kenanga",
                "area_code": "AREA-KNG",
                "area_primary_id": null,
                "area_primary_name": null,
                "area_primary_code": null,
                "splitter_id": 7,
                "spliter": "1/2/1",
                "customer_port": 4,
                "tech_extra": "extra a",
                "tech_extra_b": "extra b"
            },
            "work_order": {
                "wo_id": "1",
                "work_order_date": "2026-08-05",
                "start_work_order": "2026-08-05T08:00:00+07:00",
                "end_work_order": "2026-08-05T10:00:00+07:00",
                "duration_minute": 120,
                "detail": {
                "team_id": "3",
                "team_name": "Tapir",
                "sales_distance": 100,
                "technician_distance": 50,
                "dropcore": 30,
                "cable_status": "baru",
                "cable_history_id": 123,
                "cable_detail_history_id": 456,
                "psb_extra": "extra a",
                "psb_extra_b": "extra b"
                }
            },
            "pic": [
                {
                "role": "teknisi",
                "nip": "S-0005",
                "nick_name": "Putra",
                "full_name": "Putra",
                "order": 1
                },
                {
                "role": "sales",
                "nip": "S-0002",
                "nick_name": "Sari",
                "full_name": "Sari Wulan",
                "order": 2
                }
            ],
            "step": [
                {
                "step_id": "1",
                "step_code": "TEK-01",
                "step_name": "Mulai Penarikan Kabel",
                "order_number": 1,
                "submitted_at": "2026-08-05T08:00:00+07:00",
                "data_values": [
                    {
                    "data_code": "waktu_mulai",
                    "data_name": "Waktu Mulai",
                    "type_data": "datetime",
                    "value": "2026-08-05T08:00:00+07:00"
                    }
                ]
                },
                {
                "step_id": "2",
                "step_code": "TEK-02",
                "step_name": "Pilih Kabel Dropcore",
                "order_number": 2,
                "submitted_at": "2026-08-05T08:05:00+07:00",
                "data_values": [
                    {
                    "data_code": "jenis_kabel",
                    "data_name": "Jenis Kabel",
                    "type_data": "text",
                    "value": "Dropcore 2 Core"
                    },
                    {
                    "data_code": "panjang_kabel",
                    "data_name": "Panjang Kabel",
                    "type_data": "number",
                    "value": "30"
                    }
                ]
                },
                {
                "step_id": "3",
                "step_code": "TEK-03",
                "step_name": "Cek Redaman ODP via OPM",
                "order_number": 3,
                "submitted_at": "2026-08-05T08:15:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto ODP",
                    "type_data": "image",
                    "value": "https://indo-digital.com/wp-content/uploads/2014/10/aop05-264x300.jpg"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Pasang",
                    "type_data": "datetime",
                    "value": "2026-08-05T08:10:00+07:00"
                    },
                    {
                    "data_code": "redaman_odp",
                    "data_name": "Redaman ODP",
                    "type_data": "number",
                    "value": "0.75"
                    }
                ]
                },
                {
                "step_id": "4",
                "step_code": "TEK-04",
                "step_name": "Nilai Sambungan Patchcord-DC",
                "order_number": 4,
                "submitted_at": "2026-08-05T08:25:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto Sambungan",
                    "type_data": "image",
                    "value": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQGmgxgdAOeIiCQp-3RhnXuuVAYj4qMo02UhLTwPWrN4k3l0Ox8gBa7rUE&s=10"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Cek",
                    "type_data": "datetime",
                    "value": "2026-08-05T08:20:00+07:00"
                    },
                    {
                    "data_code": "input_nilai_sambungan_db",
                    "data_name": "Nilai Sambungan (dB)",
                    "type_data": "number",
                    "value": "0.25"
                    }
                ]
                },
                {
                "step_id": "5",
                "step_code": "TEK-05",
                "step_name": "Instalasi Patchcord ODP Pra-Tutup",
                "order_number": 5,
                "submitted_at": "2026-08-05T08:35:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto Pra-Tutup",
                    "type_data": "image",
                    "value": "https://down-id.img.susercontent.com/file/e2ac15de89b714abf21da176c2c00dc9"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Foto",
                    "type_data": "datetime",
                    "value": "2026-08-05T08:30:00+07:00"
                    }
                ]
                },
                {
                "step_id": "6",
                "step_code": "TEK-06",
                "step_name": "Tutup dan Dokumentasi ODP",
                "order_number": 6,
                "submitted_at": "2026-08-05T08:45:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto ODP Tertutup",
                    "type_data": "image",
                    "value": "https://www.researchgate.net/publication/375404038/figure/fig1/AS:11431281203306372@1699276919445/Optical-Distribution-Point-The-capacity-of-ODP-varies-according-to-needs-which-are.png"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Tutup",
                    "type_data": "datetime",
                    "value": "2026-08-05T08:40:00+07:00"
                    }
                ]
                },
                {
                "step_id": "7",
                "step_code": "TEK-07",
                "step_name": "Nilai Sambungan DC ke Patchcord Modem",
                "order_number": 7,
                "submitted_at": "2026-08-05T08:55:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto Sambungan Modem",
                    "type_data": "image",
                    "value": "https://www.promaxelectronics.com/assets/images/products/sliderProductos/prolite-44/1.jpg"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Cek",
                    "type_data": "datetime",
                    "value": "2026-08-05T08:50:00+07:00"
                    },
                    {
                    "data_code": "input_nilai_sambungan_db",
                    "data_name": "Nilai Sambungan (dB)",
                    "type_data": "number",
                    "value": "0.20"
                    }
                ]
                },
                {
                "step_id": "8",
                "step_code": "TEK-08",
                "step_name": "Redaman Pelanggan via OPM",
                "order_number": 8,
                "submitted_at": "2026-08-05T09:05:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto Redaman",
                    "type_data": "image",
                    "value": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSbTTU5gw5VmMNTO-NRBkRoeUPwkYS9H31EtYbM213BBQ&s=10"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Ukur",
                    "type_data": "datetime",
                    "value": "2026-08-05T09:00:00+07:00"
                    },
                    {
                    "data_code": "redaman_pelanggan",
                    "data_name": "Redaman Pelanggan",
                    "type_data": "number",
                    "value": "0.85"
                    }
                ]
                },
                {
                "step_id": "9",
                "step_code": "TEK-09",
                "step_name": "Registrasi & Input Ujung Kabel",
                "order_number": 9,
                "submitted_at": "2026-08-05T09:15:00+07:00",
                "data_values": [
                    {
                    "data_code": "data_registrasi",
                    "data_name": "Data Registrasi",
                    "type_data": "text",
                    "value": "ONT SN: ABC123456"
                    },
                    {
                    "data_code": "data_kabel_ujung",
                    "data_name": "Data Ujung Kabel",
                    "type_data": "text",
                    "value": "Port 4 - ODP Cluster Kenanga"
                    }
                ]
                },
                {
                "step_id": "10",
                "step_code": "TEK-10",
                "step_name": "Upload Foto Instalasi Modem",
                "order_number": 10,
                "submitted_at": "2026-08-05T09:30:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto Modem",
                    "type_data": "image",
                    "value": "https://asset.kompas.com/crops/HvZyI6sl3BIuDnvZmM4U3rATMig=/0x0:1000x667/1200x800/data/photo/2022/06/27/62b8a1de32dea.jpg"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Foto",
                    "type_data": "datetime",
                    "value": "2026-08-05T09:25:00+07:00"
                    }
                ]
                },
                {
                "step_id": "11",
                "step_code": "TEK-11",
                "step_name": "Upload Foto Speed Test",
                "order_number": 11,
                "submitted_at": "2026-08-05T09:45:00+07:00",
                "data_values": [
                    {
                    "data_code": "foto",
                    "data_name": "Foto Speed Test",
                    "type_data": "image",
                    "value": "https://www.guidingtech.com/wp-content/uploads/Speedtest-results-mobile_4d470f76dc99e18ad75087b1b8410ea9.png"
                    },
                    {
                    "data_code": "timestamp",
                    "data_name": "Waktu Test",
                    "type_data": "datetime",
                    "value": "2026-08-05T09:40:00+07:00"
                    }
                ]
                },
                {
                "step_id": "12",
                "step_code": "TEK-12",
                "step_name": "Revisi Jalur (Opsional)",
                "order_number": 12,
                "submitted_at": "2026-08-05T10:00:00+07:00",
                "data_values": [
                    {
                    "data_code": "Data Revisi",
                    "data_name": "Data Revisi",
                    "type_data": "text",
                    "value": "ACC"
                    }
                ]
                },
                {
                "step_id": "13",
                "step_code": "TEK-13",
                "step_name": "Selesaikan PSB",
                "order_number": 13,
                "submitted_at": "2026-08-05T10:00:00+07:00",
                "data_values": [
                    {
                    "data_code": "status_customer_active",
                    "data_name": "Status Customer",
                    "type_data": "text",
                    "value": "Active"
                    },
                    {
                    "data_code": "waktu_selesai",
                    "data_name": "Waktu Selesai",
                    "type_data": "datetime",
                    "value": "2026-08-05T10:00:00+07:00"
                    }
                ]
                }
            ]
            },
            "id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
        },
        "technical": {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "area_id": "Cluster Kenanga ID",
            "area_name": "Cluster Kenanga",
            "area_code": "AREA-KNG",
            "area_primary_id": null,
            "area_primary_name": null,
            "area_primary_code": null,
            "splitter_id": 7,
            "splitter": "1/2/1",
            "customer_port": 4,
            "tech_extra": {
            "tech_extra": "extra a",
            "tech_extra_b": "extra b"
            },
            "id": "01a0a47d-898f-7005-b66c-61891152ffec",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
        },
        "wo_psb": {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "team_id": "3",
            "team_name": "Tapir",
            "sales_distance": 100,
            "technician_distance": 50,
            "dropcore": 30,
            "cable_status": "baru",
            "cable_history_id": 123,
            "cable_detail_history_id": 456,
            "psb_extra": {
            "psb_extra": "extra a",
            "psb_extra_b": "extra b"
            },
            "id": "01a0a47d-8991-7111-9e46-565789242ee0",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
        },
        "pics": [
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "role": "teknisi",
            "nip": "S-0005",
            "nick_name": "Putra",
            "full_name": "Putra",
            "is_primary": 1,
            "id": "01a0a47d-8993-72f2-bbb0-195f67777c8b",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "role": "sales",
            "nip": "S-0002",
            "nick_name": "Sari",
            "full_name": "Sari Wulan",
            "is_primary": 0,
            "id": "01a0a47d-8995-7361-81d5-ab5c5138afc7",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            }
        ],
        "steps": [
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "1",
            "step_code": "TEK-01",
            "step_name": "Mulai Penarikan Kabel",
            "order_number": 1,
            "id": "01a0a47d-8996-71dd-b375-6625e91d6a02",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "2",
            "step_code": "TEK-02",
            "step_name": "Pilih Kabel Dropcore",
            "order_number": 2,
            "id": "01a0a47d-8999-71e5-8109-9374a9da20db",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "3",
            "step_code": "TEK-03",
            "step_name": "Cek Redaman ODP via OPM",
            "order_number": 3,
            "id": "01a0a47d-899c-7243-ad3c-a205b30bcdea",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "4",
            "step_code": "TEK-04",
            "step_name": "Nilai Sambungan Patchcord-DC",
            "order_number": 4,
            "id": "01a0a47d-89a0-7081-b5d3-01bdf6413d9e",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "5",
            "step_code": "TEK-05",
            "step_name": "Instalasi Patchcord ODP Pra-Tutup",
            "order_number": 5,
            "id": "01a0a47d-89a4-734b-afb6-e2258571a829",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "6",
            "step_code": "TEK-06",
            "step_name": "Tutup dan Dokumentasi ODP",
            "order_number": 6,
            "id": "01a0a47d-89a8-731c-8aef-5151a729b0a8",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "7",
            "step_code": "TEK-07",
            "step_name": "Nilai Sambungan DC ke Patchcord Modem",
            "order_number": 7,
            "id": "01a0a47d-89aa-71c1-b7de-25dcafe999f7",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "8",
            "step_code": "TEK-08",
            "step_name": "Redaman Pelanggan via OPM",
            "order_number": 8,
            "id": "01a0a47d-89ae-73d9-b6a5-d9e7d592a38a",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "9",
            "step_code": "TEK-09",
            "step_name": "Registrasi & Input Ujung Kabel",
            "order_number": 9,
            "id": "01a0a47d-89b2-70e7-9316-abbb976b769f",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "10",
            "step_code": "TEK-10",
            "step_name": "Upload Foto Instalasi Modem",
            "order_number": 10,
            "id": "01a0a47d-89b5-7089-9702-5f06ea87bc66",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "11",
            "step_code": "TEK-11",
            "step_name": "Upload Foto Speed Test",
            "order_number": 11,
            "id": "01a0a47d-89b8-734c-bf90-e3bb7ae8dbd0",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "12",
            "step_code": "TEK-12",
            "step_name": "Revisi Jalur (Opsional)",
            "order_number": 12,
            "id": "01a0a47d-89bb-7352-b7bd-adfad5185b5e",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            },
            {
            "assessment_information_id": "01a0a47d-898c-70a8-a2a7-4151ed517078",
            "step_id": "13",
            "step_code": "TEK-13",
            "step_name": "Selesaikan PSB",
            "order_number": 13,
            "id": "01a0a47d-89bd-7279-ad11-2cd6fc901d8c",
            "updated_at": "2026-09-15T09:54:45.000000Z",
            "created_at": "2026-09-15T09:54:45.000000Z"
            }
        ],
        "assessment_type": "PSB"
        }
    }
    }
    JSON;

    /**
     * Response sukses untuk endpoint update() — Update data validasi WO.
     */
    public const UPDATE_VALIDATION_WO = <<<'JSON'
    {
        "meta": {
            "status": 200,
            "message": "Berhasil menyimpan data validasi WO"
        },
        "data": {
            "id": "01a08b30-96c0-71de-a6ef-14d15702896a",
            "pppoe_secret": "dewi@griyanet",
            "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
            "work_order_id": "1",
            "current_pic_id": null,
            "company_code": "griya",
            "system_code": "griya",
            "started_at": "2026-08-05T08:00:00.000000Z",
            "completed_at": "2026-08-05T10:00:00.000000Z",
            "status_id": "01a05c7c-8344-7216-b69e-124dc33f81ba",
            "result_id": "01a05c7c-8334-71ed-b694-5716c0ab0756",
            "is_had_draft": 0,
            "created_at": "2026-09-10T12:00:12.000000Z",
            "updated_at": "2026-09-15T11:05:37.000000Z",
            "deleted_at": null,
            "details": [
                {
                    "id": "01a08ffa-db80-73a2-9e6c-cdbb24f6efaf",
                    "assessment_transaction_id": "01a08b30-96c0-71de-a6ef-14d15702896a",
                    "assessment_id": "01a05c7c-838c-706c-9b51-55db70e6ccab",
                    "assessment_name": "Instalasi Customer",
                    "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                    "total_score": 150,
                    "detail_criteria": [
                        {
                            "id": "01a08ffa-db84-713f-b742-bb84903fc2c0",
                            "assessment_detail_id": "01a08ffa-db80-73a2-9e6c-cdbb24f6efaf",
                            "criteria_id": "01a0618a-0bf0-734c-b279-4881414c20e2",
                            "criteria_name": "Redaman OPM di ODP",
                            "category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                            "category_name": "Kualitas",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 30,
                            "obtained_score": 30,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-c1e0-4772-b6c2-c120b0a045b9",
                                    "criteria_transaction_id": "01a08ffa-db84-713f-b742-bb84903fc2c0",
                                    "rule_id": "01a0618a-0c1c-7234-89cf-112298c68984",
                                    "rule_name": "Redaman di luar batas wajar",
                                    "actual_value": 1,
                                    "is_pass": 1
                                },
                                {
                                    "id": "a2b83dfd-c7a6-4025-9abc-7cc694cbcf4f",
                                    "criteria_transaction_id": "01a08ffa-db84-713f-b742-bb84903fc2c0",
                                    "rule_id": "01a0618a-0c1f-73c5-8cd9-6d858cee7872",
                                    "rule_name": "Foto OPM tidak jelas\/blur",
                                    "actual_value": 1,
                                    "is_pass": 1
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-c7b0-4fbe-bffc-3b7150249289",
                                    "criteria_transaction_id": "01a08ffa-db84-713f-b742-bb84903fc2c0",
                                    "order": 0,
                                    "step_code": "TEK-03",
                                    "variable_code": "redaman_odp",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-c7b7-45e1-88cb-e6a6a3b030a0",
                                            "assessment_step_values_id": "a2b83dfd-c7b0-4fbe-bffc-3b7150249289",
                                            "variable_name": null,
                                            "variable_code": "redaman_odp",
                                            "order": 0,
                                            "type_data": "number",
                                            "value": "0.75"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "id": "01a08ffa-db9a-70a0-a63b-7db4f5e0945c",
                            "assessment_detail_id": "01a08ffa-db80-73a2-9e6c-cdbb24f6efaf",
                            "criteria_id": "01a066c7-95d2-70bc-8c2e-fa99ff725193",
                            "criteria_name": "Sambungan Drop Core\u2013Patchcord Modem",
                            "category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                            "category_name": "Kualitas",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 20,
                            "obtained_score": 20,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-c915-4165-a54e-b9b0b727397a",
                                    "criteria_transaction_id": "01a08ffa-db9a-70a0-a63b-7db4f5e0945c",
                                    "rule_id": "01a066c7-95eb-7366-a886-760433160092",
                                    "rule_name": "Nilai sambungan di luar batas wajar",
                                    "actual_value": 1,
                                    "is_pass": 1
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-c923-481c-8359-10ceabc97067",
                                    "criteria_transaction_id": "01a08ffa-db9a-70a0-a63b-7db4f5e0945c",
                                    "order": 0,
                                    "step_code": "TEK-07",
                                    "variable_code": "input_nilai_sambungan_db",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-c92b-4d2a-9da3-4760e78403b3",
                                            "assessment_step_values_id": "a2b83dfd-c923-481c-8359-10ceabc97067",
                                            "variable_name": null,
                                            "variable_code": "input_nilai_sambungan_db",
                                            "order": 0,
                                            "type_data": "number",
                                            "value": "0.20"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "id": "01a08ffa-db9e-73ce-9b38-f855fec2ea2e",
                            "assessment_detail_id": "01a08ffa-db80-73a2-9e6c-cdbb24f6efaf",
                            "criteria_id": "01a066c9-7174-7169-bf9a-a21536c208ba",
                            "criteria_name": "Hasil Speed Test",
                            "category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                            "category_name": "Kualitas",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 50,
                            "obtained_score": 50,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-ca38-4098-964a-10d4b3ffd93b",
                                    "criteria_transaction_id": "01a08ffa-db9e-73ce-9b38-f855fec2ea2e",
                                    "rule_id": "01a066c9-718d-73ab-9295-f822696c74d3",
                                    "rule_name": "Hasil speed test di bawah paket",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-ca44-4561-bed5-88bf96bc4217",
                                    "criteria_transaction_id": "01a08ffa-db9e-73ce-9b38-f855fec2ea2e",
                                    "order": 0,
                                    "step_code": "TEK-11",
                                    "variable_code": "foto",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-ca4b-4c76-b1bb-60e94c8ba665",
                                            "assessment_step_values_id": "a2b83dfd-ca44-4561-bed5-88bf96bc4217",
                                            "variable_name": null,
                                            "variable_code": "foto",
                                            "order": 0,
                                            "type_data": "image",
                                            "value": "https:\/\/i.pcmag.com\/imagery\/articles\/03xdeDG4m4n0gJG3CbFNIgm-17..v1661444591.png"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "id": "01a08ffa-dba0-7077-9125-a620e5d02711",
                            "assessment_detail_id": "01a08ffa-db80-73a2-9e6c-cdbb24f6efaf",
                            "criteria_id": "01a066cb-1f7a-705e-8dae-1ce64eebc81a",
                            "criteria_name": "Peletakan & Instalasi Modem",
                            "category_id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                            "category_name": "Kerapian",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 100,
                            "obtained_score": 100,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-cb86-4ee0-96a0-914f8b3f6184",
                                    "criteria_transaction_id": "01a08ffa-dba0-7077-9125-a620e5d02711",
                                    "rule_id": "01a066cb-1f95-720b-b6e3-7928dff7f45c",
                                    "rule_name": "Modem tidak menempel rapi",
                                    "actual_value": 0,
                                    "is_pass": 0
                                },
                                {
                                    "id": "a2b83dfd-cb93-4cfd-bd02-31ffa82f5e7c",
                                    "criteria_transaction_id": "01a08ffa-dba0-7077-9125-a620e5d02711",
                                    "rule_id": "01a066cb-1f99-7199-aaaa-3867df184a17",
                                    "rule_name": "Kabel power tidak tertata",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-cb9d-4160-ad68-8703f2981772",
                                    "criteria_transaction_id": "01a08ffa-dba0-7077-9125-a620e5d02711",
                                    "order": 0,
                                    "step_code": "TEK-10",
                                    "variable_code": "foto",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-cba5-4803-a579-9369870d37eb",
                                            "assessment_step_values_id": "a2b83dfd-cb9d-4160-ad68-8703f2981772",
                                            "variable_name": null,
                                            "variable_code": "foto",
                                            "order": 0,
                                            "type_data": "image",
                                            "value": "https:\/\/asset.kompas.com\/crops\/HvZyI6sl3BIuDnvZmM4U3rATMig=\/0x0:1000x667\/1200x800\/data\/photo\/2022\/06\/27\/62b8a1de32dea.jpg"
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    "id": "01a08ffa-dba3-716e-8e4e-d1c9abfe0f51",
                    "assessment_transaction_id": "01a08b30-96c0-71de-a6ef-14d15702896a",
                    "assessment_id": "01a05c7c-8389-721d-b6f5-0dd692f94df9",
                    "assessment_name": "Instalasi ODP",
                    "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                    "total_score": 200,
                    "detail_criteria": [
                        {
                            "id": "01a08ffa-dba5-736d-b218-212a04008af9",
                            "assessment_detail_id": "01a08ffa-dba3-716e-8e4e-d1c9abfe0f51",
                            "criteria_id": "01a06184-c7ff-7154-9cf0-dbdd9b47ffb6",
                            "criteria_name": "Penataan Patchcord ODP",
                            "category_id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                            "category_name": "Kerapian",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 50,
                            "obtained_score": 50,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-cd36-495d-a70d-231d1f4d57dc",
                                    "criteria_transaction_id": "01a08ffa-dba5-736d-b218-212a04008af9",
                                    "rule_id": "01a06184-c87d-7263-bb8f-115495258f18",
                                    "rule_name": "Patchcord tidak tertata rapi",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-cd41-4cdd-9c5e-c8984e396089",
                                    "criteria_transaction_id": "01a08ffa-dba5-736d-b218-212a04008af9",
                                    "order": 0,
                                    "step_code": "TEK-05",
                                    "variable_code": "foto",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-cd49-4834-8aa1-0ddfaa605e6c",
                                            "assessment_step_values_id": "a2b83dfd-cd41-4cdd-9c5e-c8984e396089",
                                            "variable_name": null,
                                            "variable_code": "foto",
                                            "order": 0,
                                            "type_data": "image",
                                            "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcS7i-uwfravbCFZS4ylYchlcFSVWWTDETyeY6P3qlDlqg&s=10"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "id": "01a08ffa-dba8-704e-a268-46a8103c434d",
                            "assessment_detail_id": "01a08ffa-dba3-716e-8e4e-d1c9abfe0f51",
                            "criteria_id": "01a06676-e1c2-72b6-89c2-e19c29973464",
                            "criteria_name": "Tutup ODP",
                            "category_id": "01a05c7c-837d-7081-88ca-733f8dbc34c6",
                            "category_name": "Kerapian",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 50,
                            "obtained_score": 50,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-cdf5-4306-9a0f-2d40ca173731",
                                    "criteria_transaction_id": "01a08ffa-dba8-704e-a268-46a8103c434d",
                                    "rule_id": "01a06676-e1db-7316-a006-e479ea07f8c7",
                                    "rule_name": "Tidak ada tutup ODP",
                                    "actual_value": 0,
                                    "is_pass": 0
                                },
                                {
                                    "id": "a2b83dfd-cdfe-4404-9013-1532c074cd62",
                                    "criteria_transaction_id": "01a08ffa-dba8-704e-a268-46a8103c434d",
                                    "rule_id": "01a06676-e1df-7111-bc9f-762170bcf62c",
                                    "rule_name": "ODP pecah",
                                    "actual_value": 0,
                                    "is_pass": 0
                                },
                                {
                                    "id": "a2b83dfd-ce05-4e2e-b673-d0bd801cff80",
                                    "criteria_transaction_id": "01a08ffa-dba8-704e-a268-46a8103c434d",
                                    "rule_id": "01a06676-e1e3-7132-93b8-a6db1234a325",
                                    "rule_name": "Kunci belakang pecah",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-ce0b-48b9-b3b9-b03cbfe4f0e5",
                                    "criteria_transaction_id": "01a08ffa-dba8-704e-a268-46a8103c434d",
                                    "order": 0,
                                    "step_code": "TEK-06",
                                    "variable_code": "foto",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-ce11-4af3-b531-36ea21d71be2",
                                            "assessment_step_values_id": "a2b83dfd-ce0b-48b9-b3b9-b03cbfe4f0e5",
                                            "variable_name": null,
                                            "variable_code": "foto",
                                            "order": 0,
                                            "type_data": "image",
                                            "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSdstOtBTI492qW-eBtQ87Ww222FZQjLZTMDU41i4V7C1t6JZqrt3yGqrlM&s=10"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "id": "01a08ffa-dbaa-73a1-8822-3076c29a46bb",
                            "assessment_detail_id": "01a08ffa-dba3-716e-8e4e-d1c9abfe0f51",
                            "criteria_id": "01a06673-e10e-72e7-b24d-7dff2f6d6744",
                            "criteria_name": "Sambungan Patchcord ODP\u2013Drop Core",
                            "category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                            "category_name": "Kualitas",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 70,
                            "obtained_score": 70,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-ceca-4920-906d-6c9ccb94636a",
                                    "criteria_transaction_id": "01a08ffa-dbaa-73a1-8822-3076c29a46bb",
                                    "rule_id": "01a06673-e13e-73cb-93a1-2422583a8eba",
                                    "rule_name": "Nilai sambungan di luar batas wajar",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-ced9-4045-b116-220b27acd7a7",
                                    "criteria_transaction_id": "01a08ffa-dbaa-73a1-8822-3076c29a46bb",
                                    "order": 0,
                                    "step_code": "TEK-04",
                                    "variable_code": "input_nilai_sambungan_db",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-cee3-4e23-a019-26156ca365d2",
                                            "assessment_step_values_id": "a2b83dfd-ced9-4045-b116-220b27acd7a7",
                                            "variable_name": null,
                                            "variable_code": "input_nilai_sambungan_db",
                                            "order": 0,
                                            "type_data": "number",
                                            "value": "0.25"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "id": "01a08ffa-dbad-7008-b816-59a26d7616c6",
                            "assessment_detail_id": "01a08ffa-dba3-716e-8e4e-d1c9abfe0f51",
                            "criteria_id": "01a06674-ffbb-73df-bd4d-040b1a164c34",
                            "criteria_name": "Redaman OPM di ODP",
                            "category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                            "category_name": "Kualitas",
                            "criteria_type_id": "01a05c7c-8356-727d-b363-abf57bca2551",
                            "criteria_type_name": "Single Source",
                            "operation_id": null,
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 30,
                            "obtained_score": 30,
                            "calculated_value": null,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-d25e-4576-aa3a-1252542b463a",
                                    "criteria_transaction_id": "01a08ffa-dbad-7008-b816-59a26d7616c6",
                                    "rule_id": "01a06674-ffd8-72e5-894a-59e1c70ccbe8",
                                    "rule_name": "Redaman di luar batas wajar",
                                    "actual_value": 0,
                                    "is_pass": 0
                                },
                                {
                                    "id": "a2b83dfd-d26a-47f2-89e3-f32f60d6aab8",
                                    "criteria_transaction_id": "01a08ffa-dbad-7008-b816-59a26d7616c6",
                                    "rule_id": "01a06674-ffdc-731a-a0c0-755ba0859268",
                                    "rule_name": "Foto OPM tidak jelas\/blur",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-d272-4ab1-be22-7eb8abc3f1e5",
                                    "criteria_transaction_id": "01a08ffa-dbad-7008-b816-59a26d7616c6",
                                    "order": 0,
                                    "step_code": "TEK-03",
                                    "variable_code": "redaman_odp",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-d27a-438d-b4cf-033a8fe95179",
                                            "assessment_step_values_id": "a2b83dfd-d272-4ab1-be22-7eb8abc3f1e5",
                                            "variable_name": null,
                                            "variable_code": "redaman_odp",
                                            "order": 0,
                                            "type_data": "number",
                                            "value": "0.75"
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    "id": "01a08ffa-dbb5-715e-9081-cfaf7f038c5a",
                    "assessment_transaction_id": "01a08b30-96c0-71de-a6ef-14d15702896a",
                    "assessment_id": "01a05c7c-838f-73f8-a83c-e907a0caa285",
                    "assessment_name": "Penarikan",
                    "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                    "total_score": 100,
                    "detail_criteria": [
                        {
                            "id": "01a08ffa-dbb7-70e3-88a7-fc358a0a9ec7",
                            "assessment_detail_id": "01a08ffa-dbb5-715e-9081-cfaf7f038c5a",
                            "criteria_id": "01a066c4-ec3b-7330-9b64-1a726481d963",
                            "criteria_name": "Selisih Redaman Customer \u2212 ODP",
                            "category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                            "category_name": "Kualitas",
                            "criteria_type_id": "01a05c7c-835a-72ce-89f1-bae92944587f",
                            "criteria_type_name": "Kalkulasi",
                            "operation_id": "01a05c7c-8368-71f8-b650-d2dbb95f0331",
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 50,
                            "obtained_score": 50,
                            "calculated_value": 0.1,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-d3cd-4351-b4cd-08db53547463",
                                    "criteria_transaction_id": "01a08ffa-dbb7-70e3-88a7-fc358a0a9ec7",
                                    "rule_id": "01a066c4-ec72-7245-b82b-f36be69ee76a",
                                    "rule_name": "Selisih redaman tidak wajar",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-d3d8-4e63-a74f-a8e90596584d",
                                    "criteria_transaction_id": "01a08ffa-dbb7-70e3-88a7-fc358a0a9ec7",
                                    "order": 0,
                                    "step_code": "TEK-08",
                                    "variable_code": "redaman_pelanggan",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-d3df-4ed8-b91f-e35ea9cf1fe5",
                                            "assessment_step_values_id": "a2b83dfd-d3d8-4e63-a74f-a8e90596584d",
                                            "variable_name": null,
                                            "variable_code": "redaman_pelanggan",
                                            "order": 0,
                                            "type_data": "number",
                                            "value": "0.85"
                                        }
                                    ]
                                },
                                {
                                    "id": "a2b83dfd-d3e7-4221-af7a-fe142b12a514",
                                    "criteria_transaction_id": "01a08ffa-dbb7-70e3-88a7-fc358a0a9ec7",
                                    "order": 0,
                                    "step_code": "TEK-03",
                                    "variable_code": "redaman_odp",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-d3ee-4df2-b168-9100603af5ac",
                                            "assessment_step_values_id": "a2b83dfd-d3e7-4221-af7a-fe142b12a514",
                                            "variable_name": null,
                                            "variable_code": "redaman_odp",
                                            "order": 0,
                                            "type_data": "number",
                                            "value": "0.75"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "id": "01a08ffa-dbb9-71b4-aa44-d8396c40cd00",
                            "assessment_detail_id": "01a08ffa-dbb5-715e-9081-cfaf7f038c5a",
                            "criteria_id": "01a066c5-aa50-72b6-9638-fc7b5eb6e24d",
                            "criteria_name": "Durasi Penarikan",
                            "category_id": "01a05c7c-837a-731b-ad9b-7699b9500fa6",
                            "category_name": "Kualitas",
                            "criteria_type_id": "01a05c7c-835a-72ce-89f1-bae92944587f",
                            "criteria_type_name": "Kalkulasi",
                            "operation_id": "01a05c7c-8368-71f8-b650-d2dbb95f0331",
                            "score_type_id": "01a05c7c-8371-70f4-8ca9-1cefbef4a2fe",
                            "max_score": 50,
                            "obtained_score": 50,
                            "calculated_value": 2,
                            "description": null,
                            "detail_rules": [
                                {
                                    "id": "a2b83dfd-d48f-490e-a605-61f61dda4826",
                                    "criteria_transaction_id": "01a08ffa-dbb9-71b4-aa44-d8396c40cd00",
                                    "rule_id": "01a066c5-aa77-7183-92f9-3586b1543951",
                                    "rule_name": "Durasi pengerjaan tidak wajar",
                                    "actual_value": 0,
                                    "is_pass": 0
                                }
                            ],
                            "step_values": [
                                {
                                    "id": "a2b83dfd-d49c-4637-8f67-b0e1610a750d",
                                    "criteria_transaction_id": "01a08ffa-dbb9-71b4-aa44-d8396c40cd00",
                                    "order": 0,
                                    "step_code": "TEK-13",
                                    "variable_code": "waktu_selesai",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-d4a7-4e92-afff-8a5ea08c679d",
                                            "assessment_step_values_id": "a2b83dfd-d49c-4637-8f67-b0e1610a750d",
                                            "variable_name": null,
                                            "variable_code": "waktu_selesai",
                                            "order": 0,
                                            "type_data": "timestamp",
                                            "value": "2026-08-05T10:00:00+07:00"
                                        }
                                    ]
                                },
                                {
                                    "id": "a2b83dfd-d4ae-40a3-993d-bc532245ff91",
                                    "criteria_transaction_id": "01a08ffa-dbb9-71b4-aa44-d8396c40cd00",
                                    "order": 0,
                                    "step_code": "TEK-01",
                                    "variable_code": "waktu_mulai",
                                    "variable_name": null,
                                    "input_values": [
                                        {
                                            "id": "a2b83dfd-d4b4-4eab-8736-00b05c2ec6ca",
                                            "assessment_step_values_id": "a2b83dfd-d4ae-40a3-993d-bc532245ff91",
                                            "variable_name": null,
                                            "variable_code": "waktu_mulai",
                                            "order": 0,
                                            "type_data": "timestamp",
                                            "value": "2026-08-05T08:00:00+07:00"
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ],
            "status": {
                "id": "01a05c7c-8344-7216-b69e-124dc33f81ba",
                "code": "DRAFT",
                "name": "Draft"
            },
            "information": {
                "id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                "assessment_transaction_id": "01a08b30-96c0-71de-a6ef-14d15702896a",
                "branch_id": "BR-TMS-01",
                "branch_name": "Temas",
                "regional_name": "Jawa Timur",
                "pppoe_secret": "dewi@griyanet",
                "wo_id": "1",
                "no_wo": "1",
                "customer_name": "Dewi Lestari",
                "assessment_code_id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                "work_order_date": "2026-08-05T00:00:00.000000Z",
                "start_work_order": "2026-08-05T08:00:00.000000Z",
                "end_work_order": "2026-08-05T10:00:00.000000Z",
                "duration_minute": 120,
                "address": "Jl. Mawar No. 8",
                "cust_extra": [],
                "erp_extra": {
                    "pic": [
                        {
                            "nip": "T-0001",
                            "role": "teknisi",
                            "order": 1,
                            "full_name": "Andi Pratama",
                            "nick_name": "Andi"
                        },
                        {
                            "nip": "S-0002",
                            "role": "sales",
                            "order": 2,
                            "full_name": "Sari Wulan",
                            "nick_name": "Sari"
                        }
                    ],
                    "step": [
                        {
                            "step_id": "1",
                            "step_code": "TEK-01",
                            "step_name": "Mulai Penarikan Kabel",
                            "data_values": [
                                {
                                    "value": "2026-08-05T08:00:00+07:00",
                                    "data_code": "waktu_mulai",
                                    "data_name": "Waktu Mulai",
                                    "type_data": "datetime"
                                }
                            ],
                            "order_number": 1,
                            "submitted_at": "2026-08-05T08:00:00+07:00"
                        },
                        {
                            "step_id": "2",
                            "step_code": "TEK-02",
                            "step_name": "Pilih Kabel Dropcore",
                            "data_values": [
                                {
                                    "value": "Dropcore 2 Core",
                                    "data_code": "jenis_kabel",
                                    "data_name": "Jenis Kabel",
                                    "type_data": "text"
                                },
                                {
                                    "value": "30",
                                    "data_code": "panjang_kabel",
                                    "data_name": "Panjang Kabel",
                                    "type_data": "number"
                                }
                            ],
                            "order_number": 2,
                            "submitted_at": "2026-08-05T08:05:00+07:00"
                        },
                        {
                            "step_id": "3",
                            "step_code": "TEK-03",
                            "step_name": "Cek Redaman ODP via OPM",
                            "data_values": [
                                {
                                    "value": "https:\/\/indo-digital.com\/wp-content\/uploads\/2014\/10\/aop05-264x300.jpg",
                                    "data_code": "foto",
                                    "data_name": "Foto ODP",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T08:10:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Pasang",
                                    "type_data": "datetime"
                                },
                                {
                                    "value": "0.75",
                                    "data_code": "redaman_odp",
                                    "data_name": "Redaman ODP",
                                    "type_data": "number"
                                }
                            ],
                            "order_number": 3,
                            "submitted_at": "2026-08-05T08:15:00+07:00"
                        },
                        {
                            "step_id": "4",
                            "step_code": "TEK-04",
                            "step_name": "Nilai Sambungan Patchcord-DC",
                            "data_values": [
                                {
                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcQGmgxgdAOeIiCQp-3RhnXuuVAYj4qMo02UhLTwPWrN4k3l0Ox8gBa7rUE&s=10",
                                    "data_code": "foto",
                                    "data_name": "Foto Sambungan",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T08:20:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Cek",
                                    "type_data": "datetime"
                                },
                                {
                                    "value": "0.25",
                                    "data_code": "input_nilai_sambungan_db",
                                    "data_name": "Nilai Sambungan (dB)",
                                    "type_data": "number"
                                }
                            ],
                            "order_number": 4,
                            "submitted_at": "2026-08-05T08:25:00+07:00"
                        },
                        {
                            "step_id": "5",
                            "step_code": "TEK-05",
                            "step_name": "Instalasi Patchcord ODP Pra-Tutup",
                            "data_values": [
                                {
                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcS7i-uwfravbCFZS4ylYchlcFSVWWTDETyeY6P3qlDlqg&s=10",
                                    "data_code": "foto",
                                    "data_name": "Foto Pra-Tutup",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T08:30:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Foto",
                                    "type_data": "datetime"
                                }
                            ],
                            "order_number": 5,
                            "submitted_at": "2026-08-05T08:35:00+07:00"
                        },
                        {
                            "step_id": "6",
                            "step_code": "TEK-06",
                            "step_name": "Tutup dan Dokumentasi ODP",
                            "data_values": [
                                {
                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSdstOtBTI492qW-eBtQ87Ww222FZQjLZTMDU41i4V7C1t6JZqrt3yGqrlM&s=10",
                                    "data_code": "foto",
                                    "data_name": "Foto ODP Tertutup",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T08:40:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Tutup",
                                    "type_data": "datetime"
                                }
                            ],
                            "order_number": 6,
                            "submitted_at": "2026-08-05T08:45:00+07:00"
                        },
                        {
                            "step_id": "7",
                            "step_code": "TEK-07",
                            "step_name": "Nilai Sambungan DC ke Patchcord Modem",
                            "data_values": [
                                {
                                    "value": "https:\/\/fibconet.com\/wp-content\/uploads\/2023\/07\/otdr-1.jpg",
                                    "data_code": "foto",
                                    "data_name": "Foto Sambungan Modem",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T08:50:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Cek",
                                    "type_data": "datetime"
                                },
                                {
                                    "value": "0.20",
                                    "data_code": "input_nilai_sambungan_db",
                                    "data_name": "Nilai Sambungan (dB)",
                                    "type_data": "number"
                                }
                            ],
                            "order_number": 7,
                            "submitted_at": "2026-08-05T08:55:00+07:00"
                        },
                        {
                            "step_id": "8",
                            "step_code": "TEK-08",
                            "step_name": "Redaman Pelanggan via OPM",
                            "data_values": [
                                {
                                    "value": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSbTTU5gw5VmMNTO-NRBkRoeUPwkYS9H31EtYbM213BBQ&s=10",
                                    "data_code": "foto",
                                    "data_name": "Foto Redaman",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T09:00:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Ukur",
                                    "type_data": "datetime"
                                },
                                {
                                    "value": "0.85",
                                    "data_code": "redaman_pelanggan",
                                    "data_name": "Redaman Pelanggan",
                                    "type_data": "number"
                                }
                            ],
                            "order_number": 8,
                            "submitted_at": "2026-08-05T09:05:00+07:00"
                        },
                        {
                            "step_id": "9",
                            "step_code": "TEK-09",
                            "step_name": "Registrasi & Input Ujung Kabel",
                            "data_values": [
                                {
                                    "value": "ONT SN: ABC123456",
                                    "data_code": "data_registrasi",
                                    "data_name": "Data Registrasi",
                                    "type_data": "text"
                                },
                                {
                                    "value": "Port 4 - ODP Cluster Kenanga",
                                    "data_code": "data_kabel_ujung",
                                    "data_name": "Data Ujung Kabel",
                                    "type_data": "text"
                                }
                            ],
                            "order_number": 9,
                            "submitted_at": "2026-08-05T09:15:00+07:00"
                        },
                        {
                            "step_id": "10",
                            "step_code": "TEK-10",
                            "step_name": "Upload Foto Instalasi Modem",
                            "data_values": [
                                {
                                    "value": "https:\/\/asset.kompas.com\/crops\/HvZyI6sl3BIuDnvZmM4U3rATMig=\/0x0:1000x667\/1200x800\/data\/photo\/2022\/06\/27\/62b8a1de32dea.jpg",
                                    "data_code": "foto",
                                    "data_name": "Foto Modem",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T09:25:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Foto",
                                    "type_data": "datetime"
                                }
                            ],
                            "order_number": 10,
                            "submitted_at": "2026-08-05T09:30:00+07:00"
                        },
                        {
                            "step_id": "11",
                            "step_code": "TEK-11",
                            "step_name": "Upload Foto Speed Test",
                            "data_values": [
                                {
                                    "value": "https:\/\/i.pcmag.com\/imagery\/articles\/03xdeDG4m4n0gJG3CbFNIgm-17..v1661444591.png",
                                    "data_code": "foto",
                                    "data_name": "Foto Speed Test",
                                    "type_data": "image"
                                },
                                {
                                    "value": "2026-08-05T09:40:00+07:00",
                                    "data_code": "timestamp",
                                    "data_name": "Waktu Test",
                                    "type_data": "datetime"
                                }
                            ],
                            "order_number": 11,
                            "submitted_at": "2026-08-05T09:45:00+07:00"
                        },
                        {
                            "step_id": "12",
                            "step_code": "TEK-12",
                            "step_name": "Revisi Jalur (Opsional)",
                            "data_values": [
                                {
                                    "value": "ACC",
                                    "data_code": "Data Revisi",
                                    "data_name": "Data Revisi",
                                    "type_data": "text"
                                }
                            ],
                            "order_number": 12,
                            "submitted_at": "2026-08-05T10:00:00+07:00"
                        },
                        {
                            "step_id": "13",
                            "step_code": "TEK-13",
                            "step_name": "Selesaikan PSB",
                            "data_values": [
                                {
                                    "value": "Active",
                                    "data_code": "status_customer_active",
                                    "data_name": "Status Customer",
                                    "type_data": "text"
                                },
                                {
                                    "value": "2026-08-05T10:00:00+07:00",
                                    "data_code": "waktu_selesai",
                                    "data_name": "Waktu Selesai",
                                    "type_data": "datetime"
                                }
                            ],
                            "order_number": 13,
                            "submitted_at": "2026-08-05T10:00:00+07:00"
                        }
                    ],
                    "customer": {
                        "address": "Jl. Mawar No. 8",
                        "branch_id": "BR-TMS-01",
                        "branch_name": "Temas",
                        "pppoe_secret": "dewi@griyanet",
                        "customer_name": "Dewi Lestari",
                        "regional_name": "Jawa Timur"
                    },
                    "work_order": {
                        "wo_id": "1",
                        "detail": {
                            "team_id": "2",
                            "dropcore": 30,
                            "psb_extra": "extra a",
                            "team_name": "Tim Tapir",
                            "psb_extra_b": "extra b",
                            "cable_status": "baru",
                            "sales_distance": 100,
                            "cable_history_id": 123,
                            "technician_distance": 50,
                            "cable_detail_history_id": 456
                        },
                        "end_work_order": "2026-08-05T10:00:00+07:00",
                        "duration_minute": 120,
                        "work_order_date": "2026-08-05",
                        "start_work_order": "2026-08-05T08:00:00+07:00"
                    },
                    "technical_data": {
                        "area_id": "Cluster Kenanga ID",
                        "spliter": "1\/2\/1",
                        "area_code": "AREA-KNG",
                        "area_name": "Cluster Kenanga",
                        "tech_extra": "extra a",
                        "splitter_id": 7,
                        "tech_extra_b": "extra b",
                        "customer_port": 4,
                        "area_primary_id": null,
                        "area_primary_code": null,
                        "area_primary_name": null
                    },
                    "assessment_extra": "PSB extra"
                },
                "created_at": "2026-09-10T12:00:12.000000Z",
                "updated_at": "2026-09-10T12:00:12.000000Z",
                "deleted_at": null,
                "assessment_code": {
                    "id": "01a05c7c-8328-71c2-ac4d-1472b80561e1",
                    "code": "PSB"
                },
                "technical_information": {
                    "id": "01a08b30-96e3-71b6-ae2f-a1b5c609f490",
                    "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                    "splitter": "1\/2\/1"
                },
                "pic_informations": [
                    {
                        "id": "01a08b30-96ea-7359-bb01-9234c450d9e2",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "full_name": "Andi Pratama",
                        "is_primary": 1
                    },
                    {
                        "id": "01a08b30-96ec-7200-8ae9-c2659b6d0d2e",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "full_name": "Sari Wulan",
                        "is_primary": 0
                    }
                ],
                "wo_psb_information": {
                    "id": "01a08b30-96e7-70ac-9519-040cb5a70b84",
                    "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                    "team_name": "Tim Tapir",
                    "sales_distance": 100,
                    "technician_distance": 50,
                    "dropcore": 30
                },
                "step_values": [
                    {
                        "id": "01a08b30-96f0-7394-92ec-96774dce9867",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "1",
                        "step_code": "TEK-01",
                        "step_name": "Mulai Penarikan Kabel",
                        "order_number": 1,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-96f6-7213-980b-f7928cf5695e",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "2",
                        "step_code": "TEK-02",
                        "step_name": "Pilih Kabel Dropcore",
                        "order_number": 2,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-96fb-7254-9af5-6906fc5bd4be",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "3",
                        "step_code": "TEK-03",
                        "step_name": "Cek Redaman ODP via OPM",
                        "order_number": 3,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-9700-7294-8375-d5e099483a9b",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "4",
                        "step_code": "TEK-04",
                        "step_name": "Nilai Sambungan Patchcord-DC",
                        "order_number": 4,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-9709-73a0-be57-309d142a0893",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "5",
                        "step_code": "TEK-05",
                        "step_name": "Instalasi Patchcord ODP Pra-Tutup",
                        "order_number": 5,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-970d-732b-8955-3aa43b914850",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "6",
                        "step_code": "TEK-06",
                        "step_name": "Tutup dan Dokumentasi ODP",
                        "order_number": 6,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-9712-70b3-915f-253194f43b0d",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "7",
                        "step_code": "TEK-07",
                        "step_name": "Nilai Sambungan DC ke Patchcord Modem",
                        "order_number": 7,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-9718-7121-94f2-99a2c5bc354a",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "8",
                        "step_code": "TEK-08",
                        "step_name": "Redaman Pelanggan via OPM",
                        "order_number": 8,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-971e-72a3-a9cd-14b6241196c9",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "9",
                        "step_code": "TEK-09",
                        "step_name": "Registrasi & Input Ujung Kabel",
                        "order_number": 9,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-9722-73d7-a923-32923f295cd6",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "10",
                        "step_code": "TEK-10",
                        "step_name": "Upload Foto Instalasi Modem",
                        "order_number": 10,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-9725-732d-830c-46b1b5753cc7",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "11",
                        "step_code": "TEK-11",
                        "step_name": "Upload Foto Speed Test",
                        "order_number": 11,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-972a-7340-b9ee-5d535d487e1f",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "12",
                        "step_code": "TEK-12",
                        "step_name": "Revisi Jalur (Opsional)",
                        "order_number": 12,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    },
                    {
                        "id": "01a08b30-972d-714e-b9ea-89eef9028c08",
                        "assessment_information_id": "01a08b30-96d9-7046-bdbe-8476d14087b2",
                        "step_id": "13",
                        "step_code": "TEK-13",
                        "step_name": "Selesaikan PSB",
                        "order_number": 13,
                        "updated_at": "2026-09-10T12:00:12.000000Z"
                    }
                ]
            },
            "logs_newest": {
                "id": "01a0a4be-6b40-7064-85c1-c1e218afe047",
                "assessment_transaction_id": "01a08b30-96c0-71de-a6ef-14d15702896a",
                "changed_by": "01a05c7c-7f14-7333-a8de-568f308f9e59",
                "created_at": "2026-09-15T11:05:37.000000Z",
                "by_user": {
                    "id": "01a05c7c-7f14-7333-a8de-568f308f9e59",
                    "name": "Superadmin User"
                }
            }
        }
    }
    JSON;

    /**
     * Response sukses untuk endpoint updateOnProgress() — Update status progress validasi WO.
     */
    public const UPDATE_STATUS_PROGRESS = <<<'JSON'
    {
        "meta": {
            "status": 200,
            "message": "Berhasil update status progress validasi WO"
        },
        "data": null
    }
    JSON;

    /**
     * Response sukses untuk endpoint getWoNotComplete() — Ambil WO yang belum selesai.
     */
    public const WO_NOT_COMPLETE = <<<'JSON'
    {
        "meta": {
            "status": 200,
            "message": "Berhasil update status progress validasi WO"
        },
        "data": 12
    }
    JSON;
}
