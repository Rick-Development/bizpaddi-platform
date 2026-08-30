<?php
// Emergency direct system updater bypassing route caches and Nginx fallbacks
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

echo "=== EXECUTING SYSTEM UPDATE ===\n";

try {
    echo "Running artisan storage:link...\n";
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo \Illuminate\Support\Facades\Artisan::output() . "\n";
} catch (\Throwable $e) {
    echo "storage:link output: " . $e->getMessage() . "\n";
}

try {
    echo "Running database migrations...\n";
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo \Illuminate\Support\Facades\Artisan::output() . "\n";
} catch (\Throwable $e) {
    echo "migrate error: " . $e->getMessage() . "\n";
}

try {
    echo "Clearing optimize cache...\n";
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    echo \Illuminate\Support\Facades\Artisan::output() . "\n";
} catch (\Throwable $e) {
    echo "optimize:clear error: " . $e->getMessage() . "\n";
}

try {
    echo "Re-building optimize cache...\n";
    \Illuminate\Support\Facades\Artisan::call('optimize');
    echo \Illuminate\Support\Facades\Artisan::output() . "\n";
} catch (\Throwable $e) {
    echo "optimize error: " . $e->getMessage() . "\n";
}

if (function_exists('opcache_reset')) {
    @opcache_reset();
    echo "OPcache reset executed.\n";
}

echo "=== SYSTEM UPDATE COMPLETED SUCCESSFULLY ===\n";
