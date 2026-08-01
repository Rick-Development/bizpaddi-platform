<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UnifiedAuthController;

/*
|--------------------------------------------------------------------------
| Shared API Auth Routes (used by all 3 apps: Client, Team, Admin)
|--------------------------------------------------------------------------
| POST /api/v1/auth/login           — logs in any user type (guard param: client|team|admin)
| POST /api/v1/auth/forgot-password — sends password reset email
| POST /api/v1/auth/reset-password  — resets password via token
*/

Route::prefix('v1/auth')->group(function () {
    Route::post('login',           [UnifiedAuthController::class, 'login']);
    Route::post('forgot-password', [UnifiedAuthController::class, 'forgotPassword']);
    Route::post('reset-password',  [UnifiedAuthController::class, 'resetPassword']);
});
