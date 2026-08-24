<?php

use App\Http\Controllers\Master\PsbAssessment\PsbAssessmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('psb-assessment')->controller(PsbAssessmentController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show')->whereUuid('id');
    Route::post('/', 'store')->middleware('role:it,superadmin');
    Route::patch('/{id}', 'update')->whereUuid('id')->middleware('role:it,superadmin');
});
