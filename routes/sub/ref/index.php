<?php

use App\Http\Controllers\Ref\RefController;
use Illuminate\Support\Facades\Route;

Route::prefix('ref')->controller(RefController::class)->middleware('jwt')->group(function () {
    Route::get('/criteria-types', 'criteriaTypes');
    Route::get('/calculation-operations', 'calculationOperations');
});
