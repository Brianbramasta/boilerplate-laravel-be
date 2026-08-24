<?php

use App\Http\Controllers\Master\PsbVariable\PsbWoStepController;
use Illuminate\Support\Facades\Route;

Route::prefix('psb-variable')->controller(PsbWoStepController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::patch('/{id}', 'update')->middleware('role:it,superadmin');
});
