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

// Self-service SaaS onboarding
Route::get('/register', [\Modules\Admin\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\Modules\Admin\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');


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

Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->middleware(['web']);
Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->middleware(['web']);
Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->middleware(['web']);

Route::post('/fcm-token', [\App\Http\Controllers\Api\FcmTokenController::class, 'store'])->middleware(['web']);

// Fallback direct storage file server if public/storage symlink is missing or bypassed by web server
Route::get('/storage/{path}', function ($path) {
    // Strip accidental duplicate storage/ or domain prefixes if any
    $path = preg_replace('/^(storage\/)+/', '', $path);
    $path = ltrim($path, '/');
    $filename = basename($path);

    $possiblePaths = [
        storage_path('app/public/' . $path),
        storage_path('app/public/resumes/' . $filename),
        storage_path('app/private/' . $path),
        storage_path('app/private/resumes/' . $filename),
        storage_path('app/' . $path),
        storage_path('app/resumes/' . $filename),
        public_path('storage/' . $path),
        public_path('storage/resumes/' . $filename),
    ];

    foreach ($possiblePaths as $fullPath) {
        if (file_exists($fullPath) && !is_dir($fullPath)) {
            $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
            return response()->file($fullPath, ['Content-Type' => $mimeType]);
        }
    }

    try {
        $remoteStorageBase = config('app.remote_storage_url', 'https://bizpaddi.com/storage');
        $liveUrl = rtrim($remoteStorageBase, '/') . '/' . $path;
        $remoteResponse = \Illuminate\Support\Facades\Http::timeout(5)->get($liveUrl);
        if ($remoteResponse->successful()) {
            $localSavePath = storage_path('app/public/' . $path);
            @mkdir(dirname($localSavePath), 0755, true);
            @file_put_contents($localSavePath, $remoteResponse->body());
            $contentType = $remoteResponse->header('Content-Type') ?: 'application/octet-stream';
            return response($remoteResponse->body(), 200, ['Content-Type' => $contentType]);
        }
    } catch (\Throwable $e) {
        // ignore and fallback to 404
    }

    abort(404, 'File not found on storage server');
})->where('path', '.*');

Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    return 'Cache cleared successfully!';
});

Route::get('/deploy-git-pull', function () {
    $basePath = base_path();
    $output = shell_exec("cd {$basePath} && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1");
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    return "<pre>" . e($output) . "\nMigration & Cache clear completed!</pre>";
});



