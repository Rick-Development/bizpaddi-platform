<?php

use Illuminate\Support\Facades\Route;
use Modules\Communications\Http\Controllers\CommunicationsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('communications', CommunicationsController::class)->names('communications');
});
