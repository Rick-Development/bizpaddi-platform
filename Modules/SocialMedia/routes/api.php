<?php

use Illuminate\Support\Facades\Route;
use Modules\SocialMedia\Http\Controllers\SocialMediaController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('socialmedia', SocialMediaController::class)->names('socialmedia');
});
