<?php

use Illuminate\Support\Facades\Route;
use Modules\SocialMedia\Http\Controllers\SocialMediaController;

Route::group(['prefix' => 'admin/social-media', 'middleware' => ['web', 'auth:admin']], function () {
    Route::get('/', [SocialMediaController::class, 'index'])->name('admin.social-media.index');
    Route::get('/inbox', [SocialMediaController::class, 'inbox'])->name('admin.social-media.inbox');
    Route::get('/calendar', [SocialMediaController::class, 'calendar'])->name('admin.social-media.calendar');
    Route::get('/analytics', [SocialMediaController::class, 'analytics'])->name('admin.social-media.analytics');
});
