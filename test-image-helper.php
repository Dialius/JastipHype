<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Image Helpers...\n\n";

// Test 1: Check if helpers are loaded
echo "1. Checking if helpers are loaded:\n";
if (function_exists('image_url')) {
    echo "   ✅ image_url() exists\n";
} else {
    echo "   ❌ image_url() NOT found\n";
}

if (function_exists('product_image_url')) {
    echo "   ✅ product_image_url() exists\n";
} else {
    echo "   ❌ product_image_url() NOT found\n";
}

if (function_exists('brand_logo_url')) {
    echo "   ✅ brand_logo_url() exists\n";
} else {
    echo "   ❌ brand_logo_url() NOT found\n";
}

if (function_exists('banner_image_url')) {
    echo "   ✅ banner_image_url() exists\n";
} else {
    echo "   ❌ banner_image_url() NOT found\n";
}

if (function_exists('category_image_url')) {
    echo "   ✅ category_image_url() exists\n";
} else {
    echo "   ❌ category_image_url() NOT found\n";
}

echo "\n2. Testing image_url() with sample path:\n";
$testPath = 'products/test.jpg';
$result = image_url($testPath);
echo "   Input: {$testPath}\n";
echo "   Output: {$result}\n";

echo "\n3. Testing with null path (should return placeholder):\n";
$result = image_url(null);
echo "   Output: {$result}\n";

echo "\n4. Checking storage configuration:\n";
echo "   Default disk: " . config('filesystems.default') . "\n";
echo "   Public disk root: " . config('filesystems.disks.public.root') . "\n";
echo "   Public disk URL: " . config('filesystems.disks.public.url') . "\n";

echo "\n5. Checking if storage link exists:\n";
$publicStorage = public_path('storage');
if (is_link($publicStorage)) {
    echo "   ✅ Storage link exists at: {$publicStorage}\n";
    echo "   Points to: " . readlink($publicStorage) . "\n";
} elseif (is_dir($publicStorage)) {
    echo "   ⚠️  Storage directory exists but is not a symlink\n";
} else {
    echo "   ❌ Storage link does NOT exist\n";
    echo "   Run: php artisan storage:link\n";
}

echo "\n6. Checking actual storage directories:\n";
$storagePublic = storage_path('app/public');
if (is_dir($storagePublic)) {
    echo "   ✅ storage/app/public exists\n";
    $files = scandir($storagePublic);
    $dirs = array_filter($files, function($f) use ($storagePublic) {
        return $f !== '.' && $f !== '..' && is_dir($storagePublic . '/' . $f);
    });
    if (count($dirs) > 0) {
        echo "   Subdirectories: " . implode(', ', $dirs) . "\n";
    }
} else {
    echo "   ❌ storage/app/public does NOT exist\n";
}

echo "\n✅ Test complete!\n";
