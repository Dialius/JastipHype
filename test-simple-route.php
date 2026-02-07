<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test 1: Simple route
echo "Test 1: Simple route\n";
$request = Illuminate\Http\Request::create('/test-route', 'GET');
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n\n";

// Test 2: Storage route
echo "Test 2: Storage route\n";
$request = Illuminate\Http\Request::create('/storage/products/2sJdoVjrR9tTg9sifbU4ZLfq11gl1GSy7eH9f2O3.jpg', 'GET');
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() === 200) {
    echo "✅ SUCCESS!\n";
} else {
    echo "❌ FAIL\n";
    echo "First 200 chars: " . substr($response->getContent(), 0, 200) . "\n";
}
