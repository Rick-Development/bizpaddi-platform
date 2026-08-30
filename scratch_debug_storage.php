<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ALL SERVICE CATEGORIES ===\n";
$categories = \Modules\Accounting\Models\ServiceCategory::all();
foreach ($categories as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name}\n";
    echo "  Raw 'image' column in DB: " . var_export($cat->getRawOriginal('image'), true) . "\n";
    echo "  Model attribute \$cat->image: " . var_export($cat->image, true) . "\n";
    if ($cat->image) {
        $path = storage_path('app/public/' . preg_replace('/^\/?storage\//', '', parse_url($cat->image, PHP_URL_PATH)));
        echo "  File exists on disk? " . (file_exists($path) ? "YES ({$path})" : "NO ({$path})") . "\n";
    }
    echo "-----------------------------------\n";
}
