<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountingController;
use Modules\Accounting\Http\Controllers\WebhookController;

Route::post('webhooks/safehaven', [WebhookController::class, 'handleSafeHaven'])->name('webhooks.safehaven');

Route::group([], function () {
    Route::get('accounting', function() {
        return view('accounting::dashboard');
    })->name('accounting.dashboard');
    
    Route::resource('accountings', AccountingController::class)->names('accounting');
});
