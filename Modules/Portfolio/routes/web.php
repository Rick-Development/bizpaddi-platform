<?php

use Illuminate\Support\Facades\Route;
use Modules\Portfolio\Http\Controllers\PortfolioController;

// Public routes - no authentication required
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolios/{portfolio}', [PortfolioController::class, 'show'])->name('portfolio.show');

// Protected routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/portfolios/create', [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
});
