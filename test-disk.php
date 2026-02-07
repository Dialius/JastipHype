<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Default disk: " . config('filesystems.default') . "\n";
echo "Public disk root: " . config('filesystems.disks.public.root') . "\n";

$testFile = 'products/2sJdoVjrR9tTg9sifbU4ZLfq11gl1GSy7eH9f2O3.jpg';
echo "\nTesting file: $testFile\n";
echo "Exists: " . (Storage::disk('public')->exists($testFile) ? 'YES' : 'NO') . "\n";

if (Storage::disk('public')->exists($testFile)) {
    echo "Path: " . Storage::disk('public')->path($testFile) . "\n";
    echo "URL: " . Storage::disk('public')->url($testFile) . "\n";
    echo "Size: " . Storage::disk('public')->size($testFile) . " bytes\n";
    echo "MIME: " . Storage::disk('public')->mimeType($testFile) . "\n";
}
