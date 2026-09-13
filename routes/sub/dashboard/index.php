<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::middleware('jwt')
        ->group(function () {
            $masterRoutes = glob(__DIR__ . '/routes/*.php');
            sort($masterRoutes);
            foreach ($masterRoutes as $routeFile) {
                require $routeFile;
            }
        });
    require __DIR__ . '/PublicRoute.php';
});
