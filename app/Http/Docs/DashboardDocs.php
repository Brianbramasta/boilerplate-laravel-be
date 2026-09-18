<?php

namespace App\Http\Docs;

/**
 * Response Scribe untuk DashboardController.
 *
 * Dipisah dari controller supaya controller tetap ringkas — controller cukup
 * merujuk ke constant di sini lewat #[Response(DashboardDocs::SUCCESS, status: ...)].
 */
class DashboardDocs
{
    /**
     * Response sukses untuk endpoint clusterWorkOrder().
     */
    public const CLUSTER_WORK_ORDER = <<<'JSON'
            {
                "meta": {
                    "status": 200,
                    "message": "Berhasil mengambil data cluster work order"
                },
                "data": {
                    "total_wo_masuk": 17,
                    "menunggu_validasi": 15,
                    "draft_validasi": 0
                }
            }
    JSON;

    /**
     * Response sukses untuk endpoint clusterHistory().
     */
    public const CLUSTER_HISTORY = <<<'JSON'
            {
                "meta": {
                    "status": 200,
                    "message": "Berhasil mengambil data cluster history"
                },
                "data": {
                    "total_wo_divalidasi": 2,
                    "total_wo_normal": 1,
                    "total_variabel_abnormal": 1
                }
            }
    JSON;

    /**
     * Response sukses untuk endpoint summary().
     */
    public const SUMMARY = <<<'JSON'
            {
                "meta": {
                    "status": 200,
                    "message": "Berhasil mengambil ringkasan dashboard"
                },
                "data": {
                    "cluster_work_order": {
                        "total_wo_masuk": 17,
                        "menunggu_validasi": 15,
                        "draft_validasi": 0
                    },
                    "cluster_history": {
                        "total_wo_divalidasi": 2,
                        "total_wo_normal": 1,
                        "total_variabel_abnormal": 1
                    }
                }
            }
    JSON;

    /**
     * Response sukses untuk endpoint qcAverageMatrix().
     */
    public const QC_AVERAGE_MATRIX = <<<'JSON'
            {
                "meta": {
                    "status": 200,
                    "message": "Berhasil mengambil data rata-rata skor QC"
                },
                "data": [
                    {
                        "category_name": "Kualitas",
                        "instalasi_odp": 100,
                        "instalasi_customer": 50,
                        "penarikan": 75,
                        "rata_rata": 75
                    },
                    {
                        "category_name": "Kerapian",
                        "instalasi_odp": 100,
                        "instalasi_customer": 100,
                        "penarikan": 0,
                        "rata_rata": 100
                    }
                ]
            }
    JSON;

    /**
     * Response sukses untuk endpoint rankingKatim().
     */
    public const RANKING_KATIM = <<<'JSON'
            {
                "meta": {
                    "status": 200,
                    "message": "Berhasil mengambil data ranking Katim/Tim"
                },
                "data": [
                    {
                        "rank": 1,
                        "name": "Tim Ikan",
                        "score": 100,
                        "total_wo": 1
                    },
                    {
                        "rank": 2,
                        "name": "Tim Gajah",
                        "score": 70,
                        "total_wo": 1
                    }
                ]
            }
    JSON;

    /**
     * Response sukses untuk endpoint rankingBranch().
     */
    public const RANKING_BRANCH = <<<'JSON'
            {
                "meta": {
                    "status": 200,
                    "message": "Berhasil mengambil data ranking Branch"
                },
                "data": [
                    {
                        "rank": 1,
                        "branch_name": "Ambarawa",
                        "score": 100,
                        "total_wo": 1
                    },
                    {
                        "rank": 2,
                        "branch_name": "Karangploso",
                        "score": 70,
                        "total_wo": 1
                    }
                ]
            }
    JSON;

    /**
     * Response sukses untuk endpoint topAbnormalRules().
     */
    public const TOP_ABNORMAL_RULES = <<<'JSON'
            {
                "meta": {
                    "status": 200,
                    "message": "Berhasil mengambil data top poin standar abnormal"
                },
                "data": [
                    {
                        "rule_name": "Nilai sambungan di luar batas wajar",
                        "total_count": 2
                    },
                    {
                        "rule_name": "Redaman di luar batas wajar",
                        "total_count": 1
                    },
                    {
                        "rule_name": "Foto OPM tidak jelas/blur",
                        "total_count": 1
                    },
                    {
                        "rule_name": "Hasil speed test di bawah paket",
                        "total_count": 1
                    },
                    {
                        "rule_name": "Selisih redaman tidak wajar",
                        "total_count": 1
                    }
                ]
            }
    JSON;
}
