<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simulate a POST request to checkout
$request = Illuminate\Http\Request::create('/checkout', 'POST', [
    'email' => 'test@example.com',
    'name' => 'Test User',
    'phone' => '08123456789',
    'address' => 'Test Address 123',
    'province_id' => '6',
    'city_id' => '152',
    'postal_code' => '10620',
    'payment_method' => 'qris',
    'payment_detail' => '',
    'shipping_cost' => '0',
    'subdistrict_id' => '1',
]);

// Add CSRF token
$request->headers->set('X-CSRF-TOKEN', 'test-token');
$request->setLaravelSession(new \Illuminate\Session\Store(
    'test',
    new \Illuminate\Session\ArraySessionHandler(60)
));

echo "Testing Checkout Validation\n";
echo "============================\n\n";

try {
    $response = $kernel->handle($request);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 302) {
        echo "Redirect To: " . $response->headers->get('Location') . "\n";
        echo "✓ Form submission successful!\n";
    } elseif ($response->getStatusCode() === 422) {
        echo "✗ Validation failed\n";
        $content = json_decode($response->getContent(), true);
        if (isset($content['errors'])) {
            echo "Errors:\n";
            foreach ($content['errors'] as $field => $messages) {
                echo "  - $field: " . implode(', ', $messages) . "\n";
            }
        }
    } else {
        echo "Response: " . substr($response->getContent(), 0, 500) . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n============================\n";
