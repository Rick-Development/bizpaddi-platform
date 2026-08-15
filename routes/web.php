<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

// require __DIR__.'/auth.php';

Route::get('/test-500', function () {
    abort(500);
});

Route::get('/test-403', function () {
    abort(403);
});

// Multi-guard broadcast authentication endpoint for Reverb WebSockets & Presence channels
Route::post('/broadcasting/auth', function (\Illuminate\Http\Request $request) {
    $user = \Illuminate\Support\Facades\Auth::guard('admin')->user() 
         ?? \Illuminate\Support\Facades\Auth::guard('web')->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $request->setUserResolver(function () use ($user) {
        return $user;
    });

    return \Illuminate\Support\Facades\Broadcast::auth($request);
})->middleware(['web']);

