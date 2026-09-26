<?php

use App\Http\Controllers\HR\HRController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->controller(HRController::class)->group(function () {
    Route::get('/hr/leave-requests', 'leaveRequests')->middleware('jwt');
    Route::get('/hr/leave-requests/{id}', 'leaveRequestDetail')->middleware('jwt');
    Route::get('/hr/reports/leave-summary', 'leaveSummaryReport')->middleware('jwt');
    Route::get('/hr/reports/usage-by-type', 'usageByTypeReport')->middleware('jwt');
    Route::get('/hr/reports/usage-by-employee', 'usageByEmployeeReport')->middleware('jwt');
});