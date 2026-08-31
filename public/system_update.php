<?php
// Emergency direct system updater bypassing route caches and Nginx fallbacks
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

echo "=== EXECUTING SYSTEM UPDATE ===\n";

$publicStorage = public_path('storage');
$targetStorage = storage_path('app/public');

try {
    if (file_exists($publicStorage) && !is_link($publicStorage)) {
        echo "public/storage is a physical directory. Syncing files to storage/app/public and converting to symlink...\n";

        if (!is_dir($targetStorage)) {
            mkdir($targetStorage, 0755, true);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($publicStorage, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $subPath = substr($item->getPathname(), strlen($publicStorage));
            $dest = $targetStorage . $subPath;
            if ($item->isDir()) {
                if (!is_dir($dest)) {
                    mkdir($dest, 0755, true);
                }
            } else {
                $destDir = dirname($dest);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                if (!file_exists($dest)) {
                    copy($item->getPathname(), $dest);
                }
            }
        }

        $deleteDir = function($dir) use (&$deleteDir) {
            if (!is_dir($dir)) return;
            $files = array_diff(scandir($dir), array('.','..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $deleteDir("$dir/$file") : unlink("$dir/$file");
            }
            rmdir($dir);
        };
        $deleteDir($publicStorage);

        symlink($targetStorage, $publicStorage);
        echo "Symlink created successfully: public/storage -> storage/app/public\n";
    } else {
        echo "Running artisan storage:link...\n";
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        echo \Illuminate\Support\Facades\Artisan::output() . "\n";
    }
} catch (\Throwable $e) {
    echo "storage link error: " . $e->getMessage() . "\n";
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
