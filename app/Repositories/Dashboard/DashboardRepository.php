<?php
// app/Repositories/Dashboard/DashboardRepository.php

namespace App\Repositories\Dashboard;

use App\Interface\WorkOrder\DashboardInterface;
use App\Models\AssessmentPSB\AssessmentTransaction;
use App\Models\Master\AssessmentCriteria;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardInterface
{
    public function __construct(protected AssessmentTransaction $model) {}

    /**
     * Get total WO Masuk (semua WO termasuk yang belum complete)
     */
    public function getTotalWoMasuk($startDate = null, $endDate = null, $branchId = null)
    {
        $query = $this->model
            ->join('dt_assessment_informations as info', 'tr_assessments.id', '=', 'info.assessment_transaction_id')
            ->join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
            ->where('status.code', '!=', 'COMPLETE');

        if ($startDate && $endDate) {
            $query->whereBetween('info.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('info.branch_id', $branchId);
        }

        return $query->count();
    }

    /**
     * Get WO Menunggu Validasi (status tidak complete)
     */
    public function getWoMenungguValidasi($startDate = null, $endDate = null, $branchId = null)
    {
        $query = $this->model
            ->join('dt_assessment_informations as info', 'tr_assessments.id', '=', 'info.assessment_transaction_id')
            ->join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
            ->where('status.code', '=', 'WAITING');

        if ($startDate && $endDate) {
            $query->whereBetween('info.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('info.branch_id', $branchId);
        }

        return $query->count();
    }

    /**
     * Get Draft Validasi (status draft)
     */
    public function getDraftValidasi($startDate = null, $endDate = null, $branchId = null)
    {
        $query = $this->model
            ->join('dt_assessment_informations as info', 'tr_assessments.id', '=', 'info.assessment_transaction_id')
            ->join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
            ->where('status.code', 'DRAFT');

        if ($startDate && $endDate) {
            $query->whereBetween('info.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('info.branch_id', $branchId);
        }

        return $query->count();
    }

    /**
     * Get Total WO Divalidasi (status complete)
     */
    public function getTotalWoDivalidasi($startDate = null, $endDate = null, $branchId = null)
    {
        $query = $this->model
            ->join('dt_assessment_informations as info', 'tr_assessments.id', '=', 'info.assessment_transaction_id')
            ->join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
            ->where('status.code', 'COMPLETE');

        if ($startDate && $endDate) {
            $query->whereBetween('info.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('info.branch_id', $branchId);
        }

        return $query->count();
    }

    /**
     * Get Total WO Normal (score = 100)
     */
    public function getTotalWoNormal($startDate = null, $endDate = null, $branchId = null)
    {
        $query = $this->model->from('tr_assessments')
            ->join('dt_assessment_informations as info', 'tr_assessments.id', '=', 'info.assessment_transaction_id')
            ->join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
            ->where('status.code', 'COMPLETE')
            ->leftJoin(
                DB::raw('(
                    SELECT 
                        assessment_transaction_id,
                        SUM(total_score) as average
                    FROM tr_assessment_details
                    GROUP BY assessment_transaction_id
                ) as detail_score'),
                'tr_assessments.id',
                '=',
                'detail_score.assessment_transaction_id'
            )
            ->leftJoin(
                DB::raw('(
                    SELECT 
                        ad.assessment_transaction_id,
                        COUNT(DISTINCT CONCAT(ad.id, \'-\', dc.category_id)) as total_categories
                    FROM tr_assessment_details ad
                    LEFT JOIN tr_assessment_detail_criteria dc ON dc.assessment_detail_id = ad.id
                    GROUP BY ad.assessment_transaction_id
                ) as category_counts'),
                'tr_assessments.id',
                '=',
                'category_counts.assessment_transaction_id'
            )
            ->whereNotNull('detail_score.average')
            ->whereNotNull('category_counts.total_categories')
            ->where('category_counts.total_categories', '>', 0);

        if ($startDate && $endDate) {
            $query->whereBetween('info.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('info.branch_id', $branchId);
        }

        return $query->whereRaw('ROUND((detail_score.average / category_counts.total_categories), 2) = 100')
            ->count();
    }

    /**
     * Get Total Variabel Abnormal (score < 100)
     */
    public function getTotalVariabelAbnormal($startDate = null, $endDate = null, $branchId = null)
    {
        $query = $this->model->from('tr_assessments')
            ->join('dt_assessment_informations as info', 'tr_assessments.id', '=', 'info.assessment_transaction_id')
            ->join('ref_assessment_statuses as status', 'tr_assessments.status_id', '=', 'status.id')
            ->where('status.code', 'COMPLETE')
            ->leftJoin(
                DB::raw('(
                    SELECT 
                        assessment_transaction_id,
                        SUM(total_score) as average
                    FROM tr_assessment_details
                    GROUP BY assessment_transaction_id
                ) as detail_score'),
                'tr_assessments.id',
                '=',
                'detail_score.assessment_transaction_id'
            )
            ->leftJoin(
                DB::raw('(
                    SELECT 
                        ad.assessment_transaction_id,
                        COUNT(DISTINCT CONCAT(ad.id, \'-\', dc.category_id)) as total_categories
                    FROM tr_assessment_details ad
                    LEFT JOIN tr_assessment_detail_criteria dc ON dc.assessment_detail_id = ad.id
                    GROUP BY ad.assessment_transaction_id
                ) as category_counts'),
                'tr_assessments.id',
                '=',
                'category_counts.assessment_transaction_id'
            )
            ->whereNotNull('detail_score.average')
            ->whereNotNull('category_counts.total_categories')
            ->where('category_counts.total_categories', '>', 0);

        if ($startDate && $endDate) {
            $query->whereBetween('info.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('info.branch_id', $branchId);
        }

        return $query->whereRaw('ROUND((detail_score.average / category_counts.total_categories), 2) < 100')
            ->count();
    }

    /**
     * Get semua data dashboard dalam satu query
     */
    public function getDashboardData($startDate = null, $endDate = null, $branchId = null)
    {
        // Cluster Work Order
        $totalWoMasuk = $this->getTotalWoMasuk($startDate, $endDate, $branchId);
        $menungguValidasi = $this->getWoMenungguValidasi($startDate, $endDate, $branchId);
        $draftValidasi = $this->getDraftValidasi($startDate, $endDate, $branchId);

        // Cluster History
        $totalWoDivalidasi = $this->getTotalWoDivalidasi($startDate, $endDate, $branchId);
        $totalWoNormal = $this->getTotalWoNormal($startDate, $endDate, $branchId);
        $totalVariabelAbnormal = $this->getTotalVariabelAbnormal($startDate, $endDate, $branchId);

        return [
            'cluster_work_order' => [
                'total_wo_masuk' => $totalWoMasuk,
                'menunggu_validasi' => $menungguValidasi,
                'draft_validasi' => $draftValidasi,
            ],
            'cluster_history' => [
                'total_wo_divalidasi' => $totalWoDivalidasi,
                'total_wo_normal' => $totalWoNormal,
                'total_variabel_abnormal' => $totalVariabelAbnormal,
            ]
        ];
    }

    /**
     * Get raw data rata-rata skor QC
     */
    public function getAverageQcScore($startDate = null, $endDate = null, $branchId = null)
    {
        $query = DB::table('tr_assessment_detail_criteria as dc')
            ->join('tr_assessment_details as ad', 'dc.assessment_detail_id', '=', 'ad.id')
            ->join('tr_assessments as ta', 'ad.assessment_transaction_id', '=', 'ta.id')
            ->join('dt_assessment_informations as ita', 'ita.assessment_transaction_id', '=', 'ta.id')
            ->join('ref_assessment_statuses as status', 'ta.status_id', '=', 'status.id')
            ->join('mt_assessments as rad', 'ad.assessment_id', '=', 'rad.id')
            ->join('mt_assessment_categories as rc', 'dc.category_id', '=', 'rc.id')
            ->where('status.code', 'COMPLETE')
            ->select(
                'rad.name as detail_name',
                'rc.name as category_name',
                DB::raw('ROUND(
                     SUM(dc.obtained_score) / (
                         SELECT COUNT(DISTINCT id) 
                         FROM tr_assessments 
                         WHERE status_id IN (SELECT id FROM ref_assessment_statuses WHERE code = "COMPLETE")
                     ), 1
                 ) as average_score')
            )
            ->groupBy('rad.name', 'rc.name');

        if ($startDate && $endDate) {
            $query->whereBetween('ita.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('ita.branch_id', $branchId);
        }

        return $query->get();
    }

    /**
     * Get ranking Katim/Tim berdasarkan team_name
     * Data dari dt_assessment_wo_psb_informations
     */
    public function getRankingKatim($startDate = null, $endDate = null, $branchId = null)
    {
        $query = DB::table('dt_assessment_wo_psb_informations as dwpi')
            ->join('dt_assessment_informations as ita', 'dwpi.assessment_information_id', '=', 'ita.id')
            ->join('tr_assessments as ta', 'ita.assessment_transaction_id', '=', 'ta.id')
            ->join('ref_assessment_statuses as status', 'ta.status_id', '=', 'status.id')
            ->join('tr_assessment_details as ad', 'ta.id', '=', 'ad.assessment_transaction_id')
            ->join('tr_assessment_detail_criteria as dc', 'ad.id', '=', 'dc.assessment_detail_id')
            ->where('status.code', 'COMPLETE')
            ->select(
                'dwpi.team_name',
                DB::raw('ROUND(SUM(dc.obtained_score) / COUNT(DISTINCT ta.id), 1) as total_score'),
                DB::raw('COUNT(DISTINCT ta.id) as total_wo')
            )
            ->groupBy('dwpi.team_name')
            ->orderBy('total_score', 'DESC');

        if ($startDate && $endDate) {
            $query->whereBetween('ita.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('ita.branch_id', $branchId);
        }

        return $query->get();
    }

    /**
     * Get ranking Branch
     */
    public function getRankingBranch($startDate = null, $endDate = null)
    {
        $query = DB::table('tr_assessments as ta')
            ->join('ref_assessment_statuses as status', 'ta.status_id', '=', 'status.id')
            ->join('tr_assessment_details as ad', 'ta.id', '=', 'ad.assessment_transaction_id')
            ->join('tr_assessment_detail_criteria as dc', 'ad.id', '=', 'dc.assessment_detail_id')
            ->join('dt_assessment_informations as ita', 'ita.assessment_transaction_id', '=', 'ta.id')
            ->where('status.code', 'COMPLETE')
            ->select(
                'ita.branch_id',
                'ita.branch_name',
                DB::raw('ROUND(SUM(dc.obtained_score) / COUNT(DISTINCT ta.id), 1) as total_score'),
                DB::raw('COUNT(DISTINCT ta.id) as total_wo')
            )
            ->groupBy('ita.branch_id', 'ita.branch_name')
            ->orderBy('total_score', 'DESC');

        if ($startDate && $endDate) {
            $query->whereBetween('ita.work_order_date', [$startDate, $endDate]);
        }

        return $query->get();
    }

    /**
     * Get top poin standar abnormal (rules with actual_value = 1)
     */
    public function getTopAbnormalRules($startDate = null, $endDate = null, $branchId = null)
    {
        $query = DB::table('tr_assessment_detail_rules as tdr')
            ->join('tr_assessment_detail_criteria as dc', 'tdr.criteria_transaction_id', '=', 'dc.id')
            ->join('tr_assessment_details as ad', 'dc.assessment_detail_id', '=', 'ad.id')
            ->join('tr_assessments as ta', 'ad.assessment_transaction_id', '=', 'ta.id')
            ->join('ref_assessment_statuses as status', 'ta.status_id', '=', 'status.id')
            ->join('dt_assessment_informations as ita', 'ita.assessment_transaction_id', '=', 'ta.id')
            ->where('status.code', 'COMPLETE')
            ->where('tdr.actual_value', 1)
            ->select(
                'tdr.rule_name',
                DB::raw('COUNT(*) as total_count')
            )
            ->groupBy('tdr.rule_name')
            ->orderBy('total_count', 'DESC');

        if ($startDate && $endDate) {
            $query->whereBetween('ita.work_order_date', [$startDate, $endDate]);
        }

        if ($branchId) {
            $query->where('ita.branch_id', $branchId);
        }

        return $query->get();
    }

    public static function getTotalCategoryAssessmentGrouped()
    {
        return AssessmentCriteria::select('assessment_id', 'assessment_category_id')
            ->groupBy('assessment_id', 'assessment_category_id')
            ->get()
            ->count();
    }
}
