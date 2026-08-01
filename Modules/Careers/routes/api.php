<?php

use Illuminate\Support\Facades\Route;
use Modules\Careers\Http\Controllers\CareersController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('careers', CareersController::class)->names('careers');
});
