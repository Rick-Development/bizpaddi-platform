<?php

use Illuminate\Support\Facades\Route;
use Modules\Legal\Http\Controllers\LegalController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('legals', LegalController::class)->names('legal');
});
