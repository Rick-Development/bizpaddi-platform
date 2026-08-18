<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\ContactController;

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('/start-project', [\Modules\Contact\Http\Controllers\LeadController::class, 'create'])->name('lead.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/start-project', [\Modules\Contact\Http\Controllers\LeadController::class, 'store'])->name('lead.store');

Route::get('/fintech', [\Modules\Contact\Http\Controllers\FintechLandingPageController::class, 'index'])->name('fintech.index');
Route::post('/fintech', [\Modules\Contact\Http\Controllers\FintechLandingPageController::class, 'store'])->name('fintech.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contacts', ContactController::class);
});
