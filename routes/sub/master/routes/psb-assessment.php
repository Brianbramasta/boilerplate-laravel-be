<?php

use App\Http\Controllers\Master\PsbAssessment\PsbAssessmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('psb-assessment')->controller(PsbAssessmentController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:it,superadmin');
});
