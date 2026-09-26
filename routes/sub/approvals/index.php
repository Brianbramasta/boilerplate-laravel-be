<?php

use App\Http\Controllers\Approver\ApproverController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->controller(ApproverController::class)->group(function () {
    Route::get('/approvals/leave-requests', 'leaveRequests')->middleware('jwt');
    Route::patch('/approvals/leave-requests/{id}/approve', 'approve')->middleware('jwt');
    Route::patch('/approvals/leave-requests/{id}/reject', 'reject')->middleware('jwt');
});