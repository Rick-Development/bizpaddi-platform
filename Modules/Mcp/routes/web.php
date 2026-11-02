<?php

use Illuminate\Support\Facades\Route;
use Modules\Mcp\Http\Controllers\McpController;

Route::prefix('mcp')->name('mcp.')->group(function () {
    Route::post('/', [McpController::class, 'handle'])->name('handle');
});

Route::middleware(['web', \Modules\Admin\Http\Middleware\AdminAuth::class])->prefix('dashboard/mcp')->name('admin.mcp.')->group(function () {
    Route::get('/', [McpController::class, 'index'])->name('index');
    Route::get('/ai-generator', [McpController::class, 'aiGenerator'])->name('ai-generator');
    Route::post('/generate', [McpController::class, 'generateFromPrompt'])->name('generate');
    Route::post('/execute', [McpController::class, 'executeAction'])->name('execute');
});

// Backwards-compatible aliases
Route::redirect('admin/mcp', '/dashboard/mcp');
Route::redirect('admin/mcp/{path}', '/dashboard/mcp/{path}')->where('path', '.*');
