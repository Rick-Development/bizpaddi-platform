<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\ServicesController;

// Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
Route::resource('services', ServicesController::class)->names('services');
