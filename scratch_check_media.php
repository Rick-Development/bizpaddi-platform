<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$medias = \Spatie\MediaLibrary\MediaCollections\Models\Media::latest()->take(5)->get();
if ($medias->isEmpty()) {
    echo "No media found.\n";
    exit;
}

foreach ($medias as $m) {
    echo "ID: " . $m->id . "\n";
    echo "Model: " . $m->model_type . " (ID: " . $m->model_id . ")\n";
    echo "Collection: " . $m->collection_name . "\n";
    echo "Name: " . $m->name . "\n";
    echo "Filename: " . $m->file_name . "\n";
    echo "Mime: " . $m->mime_type . "\n";
    echo "Disk: " . $m->disk . "\n";
    echo "URL: " . $m->getUrl() . "\n";
    echo "Path: " . $m->getPath() . "\n";
    echo "-----------------\n";
}
