<?php

use Illuminate\Support\Facades\Route;
use Modules\Subsidiaries\Http\Controllers\SubsidiariesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('subsidiaries', SubsidiariesController::class)->names('subsidiaries');
});
