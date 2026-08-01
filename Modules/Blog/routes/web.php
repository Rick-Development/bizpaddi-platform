<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

// Public routes - no authentication required
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blog.show');

// Protected routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blog.destroy');
});
