<?php
// Direct test without Laravel routing

$testFile = 'storage/app/public/products/2sJdoVjrR9tTg9sifbU4ZLfq11gl1GSy7eH9f2O3.jpg';

echo "Testing direct file access:\n";
echo "File: $testFile\n";
echo "Exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n";

if (file_exists($testFile)) {
    echo "Size: " . filesize($testFile) . " bytes\n";
    echo "Readable: " . (is_readable($testFile) ? 'YES' : 'NO') . "\n";
    
    // Get MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $testFile);
    finfo_close($finfo);
    echo "MIME: $mimeType\n";
    
    echo "\n✅ File is accessible\n";
    echo "Now testing via Laravel route...\n";
} else {
    echo "\n❌ File not found\n";
}

// Test Laravel route
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/storage/products/2sJdoVjrR9tTg9sifbU4ZLfq11gl1GSy7eH9f2O3.jpg', 'GET');

echo "\nRequest URL: " . $request->getRequestUri() . "\n";

try {
    $response = $kernel->handle($request);
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Headers:\n";
    foreach ($response->headers->all() as $key => $values) {
        echo "  $key: " . implode(', ', $values) . "\n";
    }
    
    if ($response->getStatusCode() === 404) {
        echo "\n❌ Route returned 404\n";
        echo "Content: " . $response->getContent() . "\n";
    } else {
        echo "\n✅ Route works!\n";
    }
} catch (Exception $e) {
    echo "\n❌ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
