<?php

use App\Http\Controllers\WorkOrder\ValidationController;
use Illuminate\Support\Facades\Route;

Route::prefix('validation')->controller(ValidationController::class)->group(function () {
    Route::get('/', 'index')->name('validation.index');
    Route::get('/status', 'listStatus')->name('validation.status');
    Route::get('/branch', 'listBranch')->name('validation.branch');
    Route::get('/total', 'getWoNotComplete')->name('validation.getWoNotComplete');
    Route::get('/{id}', 'show')->whereUuid('id')->name('validation.show');
    Route::patch('/{id}', 'update')->whereUuid('id')->name('validation.update');
    Route::patch('/{id}/progress', 'updateOnProgress')->whereUuid('id')->name('validation.updateOnProgress');
});
