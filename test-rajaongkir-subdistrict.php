<?php

/**
 * Test RajaOngkir API V2 Subdistrict Integration
 * 
 * This script tests the RajaOngkir API V2 for fetching subdistrict (kecamatan) data
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RajaOngkirService;

echo "=== TESTING RAJAONGKIR API V2 SUBDISTRICT ===\n\n";

$service = new RajaOngkirService();

// Test cities to check
$testCities = [
    ['id' => '22', 'name' => 'Kota Bandung', 'expected' => 30],
    ['id' => '153', 'name' => 'Jakarta Selatan', 'expected' => 10],
    ['id' => '444', 'name' => 'Surabaya', 'expected' => 31],
    ['id' => '501', 'name' => 'Yogyakarta', 'expected' => 14],
    ['id' => '398', 'name' => 'Kota Semarang', 'expected' => 16],
    ['id' => '256', 'name' => 'Kota Malang', 'expected' => 5],
];

$totalSuccess = 0;
$totalFailed = 0;

foreach ($testCities as $testCity) {
    echo "Testing: {$testCity['name']} (ID: {$testCity['id']})\n";
    echo str_repeat('-', 70) . "\n";
    
    try {
        $subdistricts = $service->getSubdistricts($testCity['id']);
        
        if (empty($subdistricts)) {
            echo "❌ No subdistricts found\n";
            $totalFailed++;
        } else {
            $count = count($subdistricts);
            echo "✅ Found {$count} subdistricts";
            
            if (isset($testCity['expected'])) {
                if ($count == $testCity['expected']) {
                    echo " (Expected: {$testCity['expected']}) ✓\n";
                } else {
                    echo " (Expected: {$testCity['expected']}, Got: {$count}) ⚠️\n";
                }
            } else {
                echo "\n";
            }
            
            // Show first 5 subdistricts
            echo "\nFirst 5 subdistricts:\n";
            $displayCount = 0;
            foreach ($subdistricts as $subdistrict) {
                if ($displayCount >= 5) {
                    echo "   ... and " . ($count - 5) . " more\n";
                    break;
                }
                
                $postalInfo = $subdistrict['postal_code'] ? " (Postal: {$subdistrict['postal_code']})" : " (No postal code)";
                echo "   {$displayCount}. {$subdistrict['subdistrict_name']}{$postalInfo}\n";
                $displayCount++;
            }
            
            // Check data source
            if (isset($subdistricts[0]['subdistrict_id'])) {
                $firstId = $subdistricts[0]['subdistrict_id'];
                if (is_numeric($firstId)) {
                    echo "\n   📡 Data source: RajaOngkir API V2 (REAL DATA)\n";
                    $totalSuccess++;
                } else {
                    echo "\n   💾 Data source: Mock/Fallback Data\n";
                    $totalFailed++;
                }
            } else {
                echo "\n   ⚠️  Data source: Unknown\n";
                $totalFailed++;
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $totalFailed++;
    }
    
    echo "\n";
}

echo str_repeat('=', 70) . "\n";
echo "TEST SUMMARY:\n";
echo "  ✅ Success: {$totalSuccess}\n";
echo "  ❌ Failed: {$totalFailed}\n";
echo str_repeat('=', 70) . "\n";

if ($totalSuccess > 0 && $totalFailed == 0) {
    echo "\n🎉 ALL TESTS PASSED! RajaOngkir API V2 is working perfectly!\n";
} elseif ($totalSuccess > 0) {
    echo "\n⚠️  SOME TESTS PASSED. Check failed tests above.\n";
} else {
    echo "\n❌ ALL TESTS FAILED. Please check your RajaOngkir API key configuration.\n";
    echo "   Make sure RAJAONGKIR_API_KEY is set in your .env file.\n";
}

echo "\n";
