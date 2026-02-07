<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test 1: Check if storage files exist
echo "=== TEST 1: Check Storage Files ===\n";
$storagePath = storage_path('app/public/products');
if (is_dir($storagePath)) {
    $files = scandir($storagePath);
    $imageFiles = array_filter($files, function($file) {
        return !in_array($file, ['.', '..']);
    });
    echo "Found " . count($imageFiles) . " files in storage/app/public/products\n";
    foreach (array_slice($imageFiles, 0, 3) as $file) {
        echo "  - $file\n";
    }
} else {
    echo "Storage directory not found!\n";
}

echo "\n=== TEST 2: Test Storage Controller ===\n";
// Simulate a request to storage route
$request = Illuminate\Http\Request::create('/storage/products/test.jpg', 'GET');
try {
    $response = $kernel->handle($request);
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
    
    if ($response->getStatusCode() === 200) {
        echo "✓ Storage route is working!\n";
    } else {
        echo "✗ Storage route returned non-200 status\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST 3: Test Image Helper ===\n";
// Test image helper function
if (function_exists('image_url')) {
    $testUrl = image_url('products/test.jpg');
    echo "image_url('products/test.jpg') = $testUrl\n";
    
    $expectedUrl = config('app.url') . '/storage/products/test.jpg';
    if ($testUrl === $expectedUrl) {
        echo "✓ Image helper generates correct URL\n";
    } else {
        echo "Expected: $expectedUrl\n";
        echo "Got: $testUrl\n";
    }
} else {
    echo "✗ image_url helper not found\n";
}

echo "\n=== TEST 4: Check Database Image Paths ===\n";
try {
    // Check product images
    $productImages = DB::table('product_images')->limit(3)->get();
    echo "Sample product image paths:\n";
    foreach ($productImages as $img) {
        echo "  - {$img->image_path}\n";
        $url = image_url($img->image_path);
        echo "    URL: $url\n";
    }
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "1. Storage files exist: " . (isset($imageFiles) && count($imageFiles) > 0 ? "✓" : "✗") . "\n";
echo "2. Storage route works: " . (isset($response) && $response->getStatusCode() === 200 ? "✓" : "✗") . "\n";
echo "3. Image helper works: " . (function_exists('image_url') ? "✓" : "✗") . "\n";
echo "4. Database paths checked: " . (isset($productImages) ? "✓" : "✗") . "\n";

echo "\n=== NEXT STEPS ===\n";
echo "1. Open browser and visit: " . config('app.url') . "/storage/products/[filename]\n";
echo "2. Check if images display on product pages\n";
echo "3. Clear browser cache if needed (Ctrl+Shift+R)\n";
