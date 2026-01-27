<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

echo "=================================\n";
echo "Testing API Endpoints\n";
echo "=================================\n\n";

// Test 1: Get Provinces
echo "Test 1: GET /api/location/provinces\n";
echo "------------------------------------\n";
$request = Illuminate\Http\Request::create('/api/location/provinces', 'GET');
$response = $kernel->handle($request);
$data = json_decode($response->getContent(), true);

if (isset($data['rajaongkir']['results'])) {
    $count = count($data['rajaongkir']['results']);
    echo "✅ SUCCESS: Found {$count} provinces\n";
    echo "   Sample: " . $data['rajaongkir']['results'][0]['province'] . "\n";
} else {
    echo "❌ ERROR: Invalid response\n";
    echo "   Response: " . substr($response->getContent(), 0, 200) . "\n";
}

echo "\n";

// Test 2: Get Cities
echo "Test 2: GET /api/location/cities/6 (DKI Jakarta)\n";
echo "------------------------------------------------\n";
$request = Illuminate\Http\Request::create('/api/location/cities/6', 'GET');
$response = $kernel->handle($request);
$data = json_decode($response->getContent(), true);

if (isset($data['rajaongkir']['results'])) {
    $count = count($data['rajaongkir']['results']);
    echo "✅ SUCCESS: Found {$count} cities\n";
    if ($count > 0) {
        echo "   Sample: " . $data['rajaongkir']['results'][0]['type'] . " " . $data['rajaongkir']['results'][0]['city_name'] . "\n";
    }
} else {
    echo "❌ ERROR: Invalid response\n";
    echo "   Response: " . substr($response->getContent(), 0, 200) . "\n";
}

echo "\n";

// Test 3: Calculate Cost
echo "Test 3: POST /api/location/cost\n";
echo "--------------------------------\n";
$request = Illuminate\Http\Request::create('/api/location/cost', 'POST', [
    'destination' => 153,
    'weight' => 1000,
    'courier' => 'jne'
]);
$request->headers->set('Content-Type', 'application/json');
$request->headers->set('Accept', 'application/json');

$response = $kernel->handle($request);
$data = json_decode($response->getContent(), true);

if (isset($data['rajaongkir']['results'])) {
    echo "✅ SUCCESS: Shipping costs calculated\n";
    $results = $data['rajaongkir']['results'];
    
    if (is_array($results) && count($results) > 0) {
        foreach ($results as $courier) {
            if (isset($courier['name'])) {
                echo "   Courier: " . $courier['name'] . "\n";
                if (isset($courier['costs']) && is_array($courier['costs'])) {
                    foreach ($courier['costs'] as $cost) {
                        if (isset($cost['service']) && isset($cost['cost'][0]['value'])) {
                            $value = number_format($cost['cost'][0]['value'], 0, ',', '.');
                            $etd = $cost['cost'][0]['etd'] ?? 'N/A';
                            echo "     - {$cost['service']}: Rp {$value} ({$etd} days)\n";
                        }
                    }
                }
            }
        }
    }
} else {
    echo "❌ ERROR: Invalid response\n";
    echo "   Response: " . substr($response->getContent(), 0, 300) . "\n";
}

echo "\n";
echo "=================================\n";
echo "Test Complete!\n";
echo "=================================\n";
echo "\n";
echo "If all tests passed, the checkout page should work!\n";
echo "Open: http://localhost:8000/checkout\n";
