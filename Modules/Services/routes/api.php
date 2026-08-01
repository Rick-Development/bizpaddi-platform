<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\ServicesController;

Route::prefix('v1')->group(function () {
    Route::get('services', [ServicesController::class, 'index']);
    Route::get('services/{service}', [ServicesController::class, 'show']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('services', ServicesController::class)->except(['index', 'show']);
    });
});
