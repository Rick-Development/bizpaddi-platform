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

// Self-service SaaS onboarding (standardized under /dashboard/* prefix)
Route::get('/register', function (\Illuminate\Http\Request $request) {
    return redirect()->route('admin.register', $request->query());
})->name('register');
Route::post('/register', [\Modules\Admin\Http\Controllers\Auth\RegisterController::class, 'register'])
    ->middleware('guest:admin');

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

    // Block directory traversal or sensitive paths (.log, logs/, .env, hidden files, etc.)
    if (
        str_contains($path, '..') ||
        str_starts_with($path, 'logs') ||
        str_ends_with($path, '.log') ||
        str_contains($path, '.env') ||
        str_starts_with($path, '.')
    ) {
        abort(404);
    }

    $filename = basename($path);

    $possiblePaths = [
        public_path('storage/' . $path),
        storage_path('app/public/' . $path),
        storage_path('app/public/resumes/' . $filename),
        storage_path('app/private/' . $path),
        storage_path('app/private/resumes/' . $filename),
        storage_path('app/' . $path),
        storage_path('app/resumes/' . $filename),
    ];

    foreach ($possiblePaths as $fullPath) {
        if (file_exists($fullPath) && !is_dir($fullPath)) {
            $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    }

    // Only attempt remote storage fallback if an explicit external remote storage base URL is configured
    // and is NOT this server itself (to avoid self-referential HTTP deadlocks).
    $remoteStorageBase = config('app.remote_storage_url');
    if ($remoteStorageBase) {
        $remoteHost = parse_url($remoteStorageBase, PHP_URL_HOST);
        $currentHost = request()->getHost();

        if ($remoteHost && $remoteHost !== $currentHost && !str_ends_with($currentHost, $remoteHost)) {
            try {
                $liveUrl = rtrim($remoteStorageBase, '/') . '/' . $path;
                $remoteResponse = \Illuminate\Support\Facades\Http::timeout(2)->get($liveUrl);
                if ($remoteResponse->successful()) {
                    $localSavePath = storage_path('app/public/' . $path);
                    @mkdir(dirname($localSavePath), 0755, true);
                    @file_put_contents($localSavePath, $remoteResponse->body());
                    $contentType = $remoteResponse->header('Content-Type') ?: 'application/octet-stream';
                    return response($remoteResponse->body(), 200, [
                        'Content-Type' => $contentType,
                        'Cache-Control' => 'public, max-age=86400',
                    ]);
                }
            } catch (\Throwable $e) {
                // ignore and fallback to 404
            }
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
    $pwd = shell_exec("cd {$basePath} && pwd");
    $gitStatus = shell_exec("cd {$basePath} && git status -s 2>&1");
    $gitHead = shell_exec("cd {$basePath} && git rev-parse HEAD 2>&1");
    $output = shell_exec("cd {$basePath} && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1");
    $newHead = shell_exec("cd {$basePath} && git rev-parse HEAD 2>&1");
    $buildFiles = shell_exec("ls -la {$basePath}/public/build/assets 2>&1");
    
    // Attempt npm build if npm is available
    $npmPath = shell_exec("which npm 2>/dev/null") ? trim(shell_exec("which npm 2>/dev/null")) : null;
    if ($npmPath) {
        $output .= "\n" . shell_exec("cd {$basePath} && {$npmPath} run build 2>&1");
    }
    
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    return "<pre>PWD: {$pwd}OLD HEAD: {$gitHead}NEW HEAD: {$newHead}GIT STATUS:\n{$gitStatus}\nOUTPUT:\n{$output}\nBUILD FILES:\n{$buildFiles}\nMigration & Cache clear completed!</pre>";
});



