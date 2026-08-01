<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\ContactController;

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contacts', ContactController::class);
});
