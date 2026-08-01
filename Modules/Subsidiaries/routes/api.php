<?php

use Illuminate\Support\Facades\Route;
use Modules\Subsidiaries\Http\Controllers\SubsidiariesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('subsidiaries', SubsidiariesController::class)->names('subsidiaries');
});
