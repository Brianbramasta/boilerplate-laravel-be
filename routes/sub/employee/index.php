<?php

use App\Http\Controllers\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->controller(EmployeeController::class)->group(function () {
    Route::get('/leave-types', 'leaveTypes')->middleware('jwt');
    Route::get('/my-leave-balances', 'leaveBalances')->middleware('jwt');
    Route::get('/my-leave-requests', 'leaveRequests')->middleware('jwt');
    Route::post('/my-leave-requests', 'createLeaveRequest')->middleware('jwt');
    Route::patch('/my-leave-requests/{id}/cancel', 'cancelLeaveRequest')->middleware('jwt');
});