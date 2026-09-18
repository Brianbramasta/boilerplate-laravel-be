<?php
// app/Http/Controllers/Dashboard/DashboardController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Docs\DashboardDocs;
use App\Http\Requests\Dashboard\DashboardRequest;
use App\Services\Dashboard\DashboardService;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\Endpoint;

#[Group('Dashboard', 'Endpoint untuk kebutuhan dashboard')]
class DashboardController extends BaseController
{
    public function __construct(protected DashboardService $dashboardService) {}

    /**
     * Get cluster work order data
     */
    #[Endpoint('Get cluster work order data')]
    #[QueryParam('start_date', 'string', 'Tanggal awal', required: false, example: '2025-01-01')]
    #[QueryParam('end_date', 'string', 'Tanggal akhir', required: false, example: '2025-01-31')]
    #[QueryParam('branch_id', 'string', 'ID Branch', required: false, example: 'BR-001')]
    #[Response(DashboardDocs::CLUSTER_WORK_ORDER, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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
    #[Endpoint('Get cluster history data')]
    #[QueryParam('start_date', 'string', 'Tanggal awal', required: false, example: '2025-01-01')]
    #[QueryParam('end_date', 'string', 'Tanggal akhir', required: false, example: '2025-01-31')]
    #[QueryParam('branch_id', 'string', 'ID Branch', required: false, example: 'BR-001')]
    #[Response(DashboardDocs::CLUSTER_HISTORY, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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
     * Get dashboard summary
     */
    #[Endpoint('Get dashboard summary')]
    #[QueryParam('start_date', 'string', 'Tanggal awal', required: false, example: '2025-01-01')]
    #[QueryParam('end_date', 'string', 'Tanggal akhir', required: false, example: '2025-01-31')]
    #[QueryParam('branch_id', 'string', 'ID Branch', required: false, example: 'BR-001')]
    #[Response(DashboardDocs::SUMMARY, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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
    #[Endpoint('Get rata-rata skor QC matrix')]
    #[QueryParam('start_date', 'string', 'Tanggal awal', required: false, example: '2025-01-01')]
    #[QueryParam('end_date', 'string', 'Tanggal akhir', required: false, example: '2025-01-31')]
    #[QueryParam('branch_id', 'string', 'ID Branch', required: false, example: 'BR-001')]
    #[Response(DashboardDocs::QC_AVERAGE_MATRIX, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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
    #[Endpoint('Get ranking Katim/Tim')]
    #[QueryParam('start_date', 'string', 'Tanggal awal', required: false, example: '2025-01-01')]
    #[QueryParam('end_date', 'string', 'Tanggal akhir', required: false, example: '2025-01-31')]
    #[QueryParam('branch_id', 'string', 'ID Branch', required: false, example: 'BR-001')]
    #[Response(DashboardDocs::RANKING_KATIM, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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
    #[Endpoint('Get ranking Branch')]
    #[QueryParam('start_date', 'string', 'Tanggal awal', required: false, example: '2025-01-01')]
    #[QueryParam('end_date', 'string', 'Tanggal akhir', required: false, example: '2025-01-31')]
    #[Response(DashboardDocs::RANKING_BRANCH, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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
    #[Endpoint('Get top poin standar abnormal')]
    #[QueryParam('start_date', 'string', 'Tanggal awal', required: false, example: '2025-01-01')]
    #[QueryParam('end_date', 'string', 'Tanggal akhir', required: false, example: '2025-01-31')]
    #[QueryParam('branch_id', 'string', 'ID Branch', required: false, example: 'BR-001')]
    #[Response(DashboardDocs::TOP_ABNORMAL_RULES, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
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
