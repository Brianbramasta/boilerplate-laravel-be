<?php

use App\Http\Controllers\WorkOrder\ValidationController;
use Illuminate\Support\Facades\Route;

Route::prefix('assessments')->controller(ValidationController::class)->group(function () {
    Route::post('/', 'store')->name('validation.store');  // ← tambahkan nama
});
