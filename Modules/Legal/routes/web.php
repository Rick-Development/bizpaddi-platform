<?php

use Illuminate\Support\Facades\Route;
use Modules\Legal\Http\Controllers\LegalController;

Route::get('privacy-policy', [LegalController::class, 'page'])->defaults('slug', 'privacy-policy')->name('legal.privacy');
Route::get('terms-conditions', [LegalController::class, 'page'])->defaults('slug', 'terms-conditions')->name('legal.terms');
Route::get('legal/{slug}', [LegalController::class, 'page'])->name('legal.page');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('legals', LegalController::class)->names('legal');
});
