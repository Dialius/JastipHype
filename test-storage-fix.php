<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== STORAGE FIX TEST ===\n\n";

// Test 1: Check public/storage doesn't exist
echo "Test 1: Check public/storage folder\n";
if (file_exists('public/storage')) {
    echo "❌ FAIL: public/storage folder exists (blocking route)\n";
    echo "   Solution: Delete public/storage folder\n";
} else {
    echo "✅ PASS: public/storage folder doesn't exist (route can work)\n";
}

// Test 2: Check storage/app/public has files
echo "\nTest 2: Check storage/app/public/products\n";
$files = glob('storage/app/public/products/*');
$imageFiles = array_filter($files, function($file) {
    return !is_dir($file) && $file !== 'storage/app/public/products/.gitkeep';
});

if (count($imageFiles) > 0) {
    echo "✅ PASS: Found " . count($imageFiles) . " files\n";
    echo "   Sample: " . basename($imageFiles[0]) . "\n";
} else {
    echo "❌ FAIL: No image files found\n";
}

// Test 3: Test route
echo "\nTest 3: Test storage route\n";
try {
    $request = Illuminate\Http\Request::create('/storage/products/' . basename($imageFiles[0] ?? 'test.jpg'), 'GET');
    $response = $kernel->handle($request);
    
    if ($response->getStatusCode() === 200) {
        echo "✅ PASS: Route returns 200\n";
        echo "   Content-Type: " . $response->headers->get('Content-Type') . "\n";
    } elseif ($response->getStatusCode() === 404) {
        echo "❌ FAIL: Route returns 404 (file not found)\n";
    } else {
        echo "⚠️  WARNING: Route returns " . $response->getStatusCode() . "\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

// Test 4: Test helper function
echo "\nTest 4: Test image_url helper\n";
if (function_exists('image_url')) {
    $testPath = 'products/' . basename($imageFiles[0] ?? 'test.jpg');
    $url = image_url($testPath);
    echo "✅ PASS: Helper function works\n";
    echo "   Input: $testPath\n";
    echo "   Output: $url\n";
    
    $expectedUrl = config('app.url') . '/storage/' . $testPath;
    if ($url === $expectedUrl) {
        echo "✅ PASS: URL is correct\n";
    } else {
        echo "⚠️  WARNING: URL mismatch\n";
        echo "   Expected: $expectedUrl\n";
    }
} else {
    echo "❌ FAIL: image_url helper not found\n";
}

echo "\n=== SUMMARY ===\n";
echo "If all tests pass, images should display correctly.\n";
echo "Visit: " . config('app.url') . "/\n";
echo "Or test direct: " . config('app.url') . "/storage/products/" . basename($imageFiles[0] ?? 'test.jpg') . "\n";
