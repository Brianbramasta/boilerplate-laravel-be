<?php
// app/Services/Dashboard/DashboardService.php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\DashboardRepository;

class DashboardService
{
    public function __construct(protected DashboardRepository $dashboardRepository) {}

    /**
     * Get cluster work order data
     */
    public function getClusterWorkOrder($startDate = null, $endDate = null, $branchId = null)
    {
        return [
            'total_wo_masuk' => $this->dashboardRepository->getTotalWoMasuk($startDate, $endDate, $branchId),
            'menunggu_validasi' => $this->dashboardRepository->getWoMenungguValidasi($startDate, $endDate, $branchId),
            'draft_validasi' => $this->dashboardRepository->getDraftValidasi($startDate, $endDate, $branchId),
        ];
    }

    /**
     * Get cluster history data
     */
    public function getClusterHistory($startDate = null, $endDate = null, $branchId = null)
    {
        return [
            'total_wo_divalidasi' => $this->dashboardRepository->getTotalWoDivalidasi($startDate, $endDate, $branchId),
            'total_wo_normal' => $this->dashboardRepository->getTotalWoNormal($startDate, $endDate, $branchId),
            'total_variabel_abnormal' => $this->dashboardRepository->getTotalVariabelAbnormal($startDate, $endDate, $branchId),
        ];
    }

    /**
     * Get rata-rata skor QC dalam format matrix
     */
    public function getAverageQcScoreMatrix($startDate = null, $endDate = null, $branchId = null)
    {
        // Get raw data dari repository
        $results = $this->dashboardRepository->getAverageQcScore($startDate, $endDate, $branchId);

        // Format data menjadi matrix
        $matrix = [];
        $rowTotals = [];

        foreach ($results as $row) {
            $detailName = $row->detail_name;
            $categoryName = $row->category_name;
            $score = (float) $row->average_score;

            if (!isset($matrix[$detailName])) {
                $matrix[$detailName] = [];
            }

            $matrix[$detailName][$categoryName] = $score;

            if (!isset($rowTotals[$detailName])) {
                $rowTotals[$detailName] = [];
            }
            $rowTotals[$detailName][] = $score;
        }

        // Format ke array sesuai yang diinginkan
        $result = [];

        // Daftar category yang akan ditampilkan di matrix
        $categoriesList = ['Kualitas', 'Kerapian'];

        // Daftar detail (kolom) yang dijadikan property
        $detailsList = ['Instalasi ODP', 'Instalasi Customer', 'Penarikan'];

        // Persiapkan struktur hasil
        foreach ($categoriesList as $category) {
            $row = [
                'category_name' => $category,
                'instalasi_odp' => 0,
                'instalasi_customer' => 0,
                'penarikan' => 0,
                'rata_rata' => 0
            ];

            $total = 0;
            $count = 0;

            foreach ($matrix as $detailName => $categoryScores) {
                // Cocokkan detail terhadap kolom detail
                if (in_array(strtolower($detailName), array_map('strtolower', $detailsList))) {
                    // Pilih kolom berdasarkan detail
                    $key = '';
                    if (stripos($detailName, 'odp') !== false) $key = 'instalasi_odp';
                    elseif (stripos($detailName, 'customer') !== false) $key = 'instalasi_customer';
                    elseif (stripos($detailName, 'penarikan') !== false) $key = 'penarikan';

                    // Assign skor jika ada
                    if ($key && isset($categoryScores[$category])) {
                        $row[$key] = $categoryScores[$category];
                        $total += $categoryScores[$category];
                        $count++;
                    }
                }
            }

            // Hitung rata-rata jika ada data
            $row['rata_rata'] = $count ? round($total / $count, 1) : 0;
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Get ranking Katim/Tim
     */
    public function getRankingKatim($startDate = null, $endDate = null, $branchId = null)
    {
        $results = $this->dashboardRepository->getRankingKatim($startDate, $endDate, $branchId);
        $categoryCount = $this->dashboardRepository->getTotalCategoryAssessmentGrouped();

        $rank = 1;
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'rank' => $rank++,
                'name' => $row->team_name,
                'score' => (float) $row->total_score / $categoryCount ?? 1,
                'total_wo' => (int) $row->total_wo
            ];
        }

        return $data;
    }

    /**
     * Get ranking Branch
     */
    public function getRankingBranch($startDate = null, $endDate = null)
    {
        $results = $this->dashboardRepository->getRankingBranch($startDate, $endDate);
        $categoryCount = $this->dashboardRepository->getTotalCategoryAssessmentGrouped();

        $rank = 1;
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'rank' => $rank++,
                'branch_name' => $row->branch_name,
                'score' => (float) $row->total_score / $categoryCount ?? 1,
                'total_wo' => (int) $row->total_wo
            ];
        }

        return $data;
    }

    /**
     * Get top poin standar abnormal
     */
    public function getTopAbnormalRules($startDate = null, $endDate = null, $branchId = null)
    {
        $results = $this->dashboardRepository->getTopAbnormalRules($startDate, $endDate, $branchId);

        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'rule_name' => $row->rule_name,
                'total_count' => (int) $row->total_count
            ];
        }

        return $data;
    }
}
