<?php

use Illuminate\Support\Facades\Route;
use Modules\Communications\Http\Controllers\CommunicationsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('communications', CommunicationsController::class)->names('communications');
});
