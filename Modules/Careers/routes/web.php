<?php

use Illuminate\Support\Facades\Route;
use Modules\Careers\Http\Controllers\CareersController;

// Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('careers', CareersController::class)->names('careers');
// });
