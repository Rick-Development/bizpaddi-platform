<?php

use Illuminate\Support\Facades\Route;
use Modules\Investor\Http\Controllers\InvestorController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('investors', InvestorController::class)->names('investor');
});
