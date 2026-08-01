<?php

use Illuminate\Support\Facades\Route;
use Modules\Home\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('homes', HomeController::class)->names('home_resource');
});
