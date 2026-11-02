<?php

use Illuminate\Support\Facades\Route;
use Modules\Home\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('homes', HomeController::class)->names('home_resource');
});
