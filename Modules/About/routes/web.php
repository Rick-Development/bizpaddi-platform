<?php

use Illuminate\Support\Facades\Route;
use Modules\About\Http\Controllers\AboutController;

Route::get('/about', [AboutController::class, 'index'])->name('about.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('abouts', AboutController::class);
});
