<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->group(function () {
    // Main dashboard endpoints
    Route::get('/summary', 'summary')->name('dashboard.summary');

    // Cluster Work Order
    Route::get('/cluster-work-order', 'clusterWorkOrder')->name('dashboard.cluster.work.order');

    // Cluster History
    Route::get('/cluster-history', 'clusterHistory')->name('dashboard.cluster.history');

    Route::get('/qc-average', 'qcAverageMatrix')->name('dashboard.qc.average');

    Route::get('/ranking-katim', 'rankingKatim')->name('dashboard.ranking.katim');

    Route::get('/ranking-branch', 'rankingBranch')->name('dashboard.ranking.branch');

    Route::get('/top-abnormal-rules', 'topAbnormalRules')->name('dashboard.top.abnormal.rules');
});
