<?php

use Illuminate\Support\Facades\Route;
use Modules\SocialMedia\Http\Controllers\SocialMediaController;

Route::prefix('dashboard/social-media')
    ->name('admin.social-media.')
    ->middleware(['web', \Modules\Admin\Http\Middleware\AdminAuth::class])
    ->group(function () {
        Route::get('/', [SocialMediaController::class, 'index'])->name('index');
        Route::get('/inbox', [SocialMediaController::class, 'inbox'])->name('inbox');
        Route::get('/calendar', [SocialMediaController::class, 'calendar'])->name('calendar');
        Route::get('/analytics', [SocialMediaController::class, 'analytics'])->name('analytics');
    });

// Fallback redirect for legacy links
Route::get('admin/social-media/{path?}', function ($path = null) {
    return redirect('/dashboard/social-media' . ($path ? '/' . $path : ''));
})->where('path', '.*')->middleware(['web']);
