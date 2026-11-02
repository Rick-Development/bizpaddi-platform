<?php

use Illuminate\Support\Facades\Route;
use Modules\AI\Http\Controllers\AIController;

use Modules\AI\Http\Controllers\ChatController;
use Modules\AI\Http\Controllers\Admin\AISettingController;
use Modules\AI\Http\Controllers\Admin\ChatHistoryController;

// Public Chat Routes
Route::prefix('ai/chat')->name('ai.chat.')->middleware(['web'])->group(function () {
    Route::post('/start', [ChatController::class, 'start'])->name('start');
    Route::post('/send', [ChatController::class, 'send'])->name('send');
});

// Business Dashboard Routes (Settings & History)
Route::prefix('dashboard/ai')->name('admin.ai.')->middleware(['web', \Modules\Admin\Http\Middleware\AdminAuth::class])->group(function () {
    // Settings
    Route::get('/settings', [AISettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AISettingController::class, 'update'])->name('settings.update');

    // Chat History
    Route::get('/chats', [ChatHistoryController::class, 'index'])->name('chats.index');
    Route::get('/chats/{id}', [ChatHistoryController::class, 'show'])->name('chats.show');

    // AI Models
    Route::resource('models', Modules\AI\Http\Controllers\Admin\AIModelController::class);
});

// Backwards-compatible aliases
Route::redirect('admin/ai', '/dashboard/ai');
Route::redirect('admin/ai/{path}', '/dashboard/ai/{path}')->where('path', '.*');
