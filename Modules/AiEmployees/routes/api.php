<?php

use Illuminate\Support\Facades\Route;
use Modules\AiEmployees\Http\Controllers\AiEmployeesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('aiemployees', AiEmployeesController::class)->names('aiemployees');
});
