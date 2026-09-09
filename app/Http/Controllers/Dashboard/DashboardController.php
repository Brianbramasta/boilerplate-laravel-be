<?php
// app/Http/Controllers/Dashboard/DashboardController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\DashboardRequest;
use App\Services\Dashboard\DashboardService;

class DashboardController extends BaseController
{
    public function __construct(protected DashboardService $dashboardService) {}

    /**
     * Get cluster work order data
     */
    public function clusterWorkOrder(DashboardRequest $request)
    {
        try {
            $data = $this->dashboardService->getClusterWorkOrder(
                $request->start_date,
                $request->end_date,
                $request->branch_id
            );

            return $this->successResponse($data, 'Berhasil mengambil data cluster work order');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Get cluster history data
     */
    public function clusterHistory(DashboardRequest $request)
    {
        try {
            $data = $this->dashboardService->getClusterHistory(
                $request->start_date,
                $request->end_date,
                $request->branch_id
            );

            return $this->successResponse($data, 'Berhasil mengambil data cluster history');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Get list branch for filter
     */
    public function listBranch()
    {
        try {
            // Data ini bisa diambil dari service atau repository
            $data = [
                [
                    "id" => "all",
                    "branch_name" => "All Branch",
                    "kode_branch" => "ALL",
                    "address" => "-",
                    "regional" => "-",
                    "company_name" => "-"
                ],
                [
                    "id" => 1,
                    "branch_name" => "Temas",
                    "kode_branch" => "G",
                    "address" => "Jl. Dewi Sartika I No.13, Temas, Kec. Batu, Kota Batu, Jawa Timur 65315",
                    "regional" => "Batu",
                    "company_name" => "GriyaNet"
                ],
                [
                    "id" => 7,
                    "branch_name" => "Pasuruan Selatan",
                    "kode_branch" => "PS",
                    "address" => "Jl. Panglima Sudirman No.90, Pasuruan, Jawa Timur",
                    "regional" => "Pasuruan",
                    "company_name" => "Dasarata"
                ]
            ];

            return $this->successResponse($data, 'Berhasil mengambil data branch');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Get dashboard summary
     */
    public function summary(DashboardRequest $request)
    {
        try {
            $data = [
                'cluster_work_order' => $this->dashboardService->getClusterWorkOrder(
                    $request->start_date,
                    $request->end_date,
                    $request->branch_id
                ),
                'cluster_history' => $this->dashboardService->getClusterHistory(
                    $request->start_date,
                    $request->end_date,
                    $request->branch_id
                )
            ];

            return $this->successResponse($data, 'Berhasil mengambil ringkasan dashboard');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Get rata-rata skor QC matrix sesuai gambar
     */
    public function qcAverageMatrix(DashboardRequest $request)
    {
        try {
            $data = $this->dashboardService->getAverageQcScoreMatrix(
                $request->start_date,
                $request->end_date,
                $request->branch_id
            );

            return $this->successResponse($data, 'Berhasil mengambil data rata-rata skor QC');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Get ranking Katim/Tim
     */
    public function rankingKatim(DashboardRequest $request)
    {
        try {
            $data = $this->dashboardService->getRankingKatim(
                $request->start_date,
                $request->end_date,
                $request->branch_id
            );

            return $this->successResponse($data, 'Berhasil mengambil data ranking Katim/Tim');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Get ranking Branch
     */
    public function rankingBranch(DashboardRequest $request)
    {
        try {
            $data = $this->dashboardService->getRankingBranch(
                $request->start_date,
                $request->end_date
            );

            return $this->successResponse($data, 'Berhasil mengambil data ranking Branch');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Get top poin standar abnormal
     */
    public function topAbnormalRules(DashboardRequest $request)
    {
        try {
            $data = $this->dashboardService->getTopAbnormalRules(
                $request->start_date,
                $request->end_date,
                $request->branch_id
            );

            return $this->successResponse($data, 'Berhasil mengambil data top poin standar abnormal');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}
