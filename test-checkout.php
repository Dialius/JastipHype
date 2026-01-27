<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=================================\n";
echo "Testing Checkout Features\n";
echo "=================================\n\n";

// Test 1: RajaOngkir Service
echo "Test 1: RajaOngkir Service\n";
echo "----------------------------\n";
try {
    $service = app(\App\Services\RajaOngkirService::class);
    
    // Test get provinces
    echo "Getting provinces...\n";
    $provinces = $service->getProvinces();
    
    if (!empty($provinces)) {
        echo "✅ SUCCESS: Found " . count($provinces) . " provinces\n";
        echo "   Sample: " . $provinces[0]['province'] . "\n";
    } else {
        echo "❌ ERROR: No provinces found\n";
    }
    
    // Test get cities
    echo "\nGetting cities for DKI Jakarta (province_id=6)...\n";
    $cities = $service->getCities(6);
    
    if (!empty($cities)) {
        echo "✅ SUCCESS: Found " . count($cities) . " cities\n";
        echo "   Sample: " . $cities[0]['type'] . " " . $cities[0]['city_name'] . "\n";
    } else {
        echo "❌ ERROR: No cities found\n";
    }
    
    // Test calculate cost
    echo "\nCalculating shipping cost (Jakarta Barat -> Jakarta Selatan, 1kg, JNE)...\n";
    $cost = $service->getCost(151, 153, 1000, 'jne');
    
    if (!empty($cost)) {
        echo "✅ SUCCESS: Shipping cost calculated\n";
        if (isset($cost[0]['costs'][0]['cost'][0]['value'])) {
            $value = $cost[0]['costs'][0]['cost'][0]['value'];
            echo "   Cost: Rp " . number_format($value, 0, ',', '.') . "\n";
        }
    } else {
        echo "❌ ERROR: Failed to calculate cost\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Location Controller Routes
echo "Test 2: API Routes\n";
echo "----------------------------\n";
echo "Available routes:\n";
echo "  GET  /api/location/provinces\n";
echo "  GET  /api/location/cities/{province}\n";
echo "  POST /api/location/cost\n";
echo "\n";

// Test 3: Config
echo "Test 3: Configuration\n";
echo "----------------------------\n";
$config = config('services.rajaongkir');
echo "API Key: " . substr($config['key'], 0, 10) . "...\n";
echo "Type: " . $config['type'] . "\n";
echo "Origin: " . $config['origin'] . "\n";
echo "\n";

echo "=================================\n";
echo "Test Complete!\n";
echo "=================================\n";
echo "\n";
echo "Next Steps:\n";
echo "1. Start Laravel server: php artisan serve\n";
echo "2. Open checkout page: http://localhost:8000/checkout\n";
echo "3. Test all features in browser\n";
