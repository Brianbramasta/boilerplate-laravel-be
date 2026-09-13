<?php

namespace App\Interface\WorkOrder;

interface DashboardInterface
{
    /**
     * Get total WO Masuk (semua WO termasuk yang belum complete)
     */
    public function getTotalWoMasuk(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get WO Menunggu Validasi (status tidak complete)
     */
    public function getWoMenungguValidasi(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get Draft Validasi (status draft)
     */
    public function getDraftValidasi(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get Total WO Divalidasi (status complete)
     */
    public function getTotalWoDivalidasi(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get Total WO Normal (score = 100)
     */
    public function getTotalWoNormal(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get Total Variabel Abnormal (score < 100)
     */
    public function getTotalVariabelAbnormal(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get semua data dashboard dalam satu query
     */
    public function getDashboardData(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get raw data rata-rata skor QC
     */
    public function getAverageQcScore(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get ranking Katim/Tim berdasarkan team_name
     */
    public function getRankingKatim(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get ranking Branch
     */
    public function getRankingBranch(?string $startDate = null, ?string $endDate = null);

    /**
     * Get top poin standar abnormal (rules with actual_value = 1)
     */
    public function getTopAbnormalRules(?string $startDate = null, ?string $endDate = null, ?string $branchId = null);

    /**
     * Get total grouped category for assessment
     */
    public static function getTotalCategoryAssessmentGrouped();
}
