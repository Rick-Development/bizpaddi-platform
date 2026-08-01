<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\StoreController;

Route::middleware(['web'])->group(function () {
    Route::get('store', [StoreController::class, 'index'])->name('store.index');
});
