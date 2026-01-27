<?php

/**
 * Test Kodepos API Integration
 * 
 * This script tests the Kodepos API integration for fetching real subdistrict data
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RajaOngkirService;

echo "=== TESTING KODEPOS API INTEGRATION ===\n\n";

$service = new RajaOngkirService();

// Test cities to check
$testCities = [
    ['id' => '22', 'name' => 'Kota Bandung'],
    ['id' => '153', 'name' => 'Jakarta Selatan'],
    ['id' => '444', 'name' => 'Surabaya'],
    ['id' => '501', 'name' => 'Yogyakarta'],
    ['id' => '398', 'name' => 'Kota Semarang'],
];

foreach ($testCities as $testCity) {
    echo "Testing: {$testCity['name']} (ID: {$testCity['id']})\n";
    echo str_repeat('-', 60) . "\n";
    
    try {
        $subdistricts = $service->getSubdistricts($testCity['id']);
        
        if (empty($subdistricts)) {
            echo "❌ No subdistricts found\n\n";
            continue;
        }
        
        echo "✅ Found " . count($subdistricts) . " subdistricts:\n";
        
        // Show first 5 subdistricts
        $count = 0;
        foreach ($subdistricts as $subdistrict) {
            if ($count >= 5) {
                echo "   ... and " . (count($subdistricts) - 5) . " more\n";
                break;
            }
            
            echo "   - {$subdistrict['subdistrict_name']} (Postal: {$subdistrict['postal_code']})\n";
            $count++;
        }
        
        // Check if data is from Kodepos API (starts with 'kp_') or mock data
        $firstId = $subdistricts[0]['subdistrict_id'];
        if (strpos($firstId, 'kp_') === 0) {
            echo "   📡 Data source: Kodepos API (REAL DATA)\n";
        } else {
            echo "   💾 Data source: Mock/Fallback Data\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "=== TEST COMPLETE ===\n";
