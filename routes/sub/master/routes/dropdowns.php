<?php

use App\Http\Controllers\Master\Dropdown\MasterDropdownController;
use Illuminate\Support\Facades\Route;

Route::prefix('dropdowns')->controller(MasterDropdownController::class)->group(function () {
    Route::get('/assessments', 'assessments');
    Route::get('/categories', 'categories');
});
