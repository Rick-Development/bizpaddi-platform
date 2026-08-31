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

Route::get('/debug-app-340', function () {
    header('Content-Type: text/plain');
    echo "base_path(): " . base_path() . "\n";
    echo "storage_path(): " . storage_path() . "\n";
    echo "public_path(): " . public_path() . "\n";
    echo "storage_path('app/public'): " . storage_path('app/public') . "\n";
    echo "realpath(storage_path('app/public')): " . realpath(storage_path('app/public')) . "\n";
    echo "public_path('storage'): " . public_path('storage') . "\n";
    echo "is_link(public_path('storage')): " . (is_link(public_path('storage')) ? "YES -> " . readlink(public_path('storage')) : "NO") . "\n\n";

    echo "SEARCHING ENTIRE SERVER DISK FOR KFcNCArOgYennijGNN3OBXSEVicGej6NWbOmo5H7.pdf...\n";
    $cmd = "find /home /tmp /var/www /var/tmp -name '*KFcNCArOgYennijGNN3OBXSEVicGej6NWbOmo5H7*' 2>/dev/null";
    $output = shell_exec($cmd);
    echo "Find output:\n" . ($output ?: "NONE FOUND WITH FIND COMMAND!") . "\n";
});

