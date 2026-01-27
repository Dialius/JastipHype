<?php

/**
 * Test Hybrid Subdistrict Solution - 3-Layer Fallback Strategy
 * 
 * Tests:
 * - Layer 1: RajaOngkir API V2 (for major cities)
 * - Layer 2: Kodepos API (for cities not in RajaOngkir)
 * - Layer 3: Mock Data (last resort fallback)
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Cache;

echo "=== TESTING HYBRID SUBDISTRICT SOLUTION ===\n";
echo "3-Layer Fallback Strategy Test\n\n";

$service = new RajaOngkirService();

// Test cities covering all 3 layers
$testCities = [
    // Layer 1: Cities with RajaOngkir data
    ['id' => '22', 'name' => 'Kota Bandung', 'expected_layer' => 1, 'expected_count' => 30],
    ['id' => '153', 'name' => 'Jakarta Selatan', 'expected_layer' => 1, 'expected_count' => 10],
    ['id' => '444', 'name' => 'Surabaya', 'expected_layer' => 1, 'expected_count' => 31],
    
    // Layer 2: Cities without RajaOngkir data (will use Kodepos)
    ['id' => '501', 'name' => 'Kota Yogyakarta', 'expected_layer' => 2, 'expected_count' => null],
    ['id' => '398', 'name' => 'Kota Semarang', 'expected_layer' => 2, 'expected_count' => null],
    ['id' => '256', 'name' => 'Kota Malang', 'expected_layer' => 2, 'expected_count' => null],
];

$layerStats = [
    1 => ['name' => 'RajaOngkir API', 'count' => 0, 'cities' => []],
    2 => ['name' => 'Kodepos API', 'count' => 0, 'cities' => []],
    3 => ['name' => 'Mock Data', 'count' => 0, 'cities' => []],
];

foreach ($testCities as $testCity) {
    echo str_repeat('=', 70) . "\n";
    echo "Testing: {$testCity['name']} (ID: {$testCity['id']})\n";
    echo "Expected Layer: {$testCity['expected_layer']} - " . $layerStats[$testCity['expected_layer']]['name'] . "\n";
    echo str_repeat('-', 70) . "\n";
    
    // Clear cache for fresh test
    Cache::forget("rajaongkir_subdistricts_{$testCity['id']}");
    
    try {
        $subdistricts = $service->getSubdistricts($testCity['id']);
        
        if (empty($subdistricts)) {
            echo "❌ No subdistricts found\n\n";
            continue;
        }
        
        $count = count($subdistricts);
        echo "✅ Found {$count} subdistricts\n";
        
        // Detect which layer was used
        $firstId = $subdistricts[0]['subdistrict_id'];
        $detectedLayer = 3; // Default to mock
        
        if (is_numeric($firstId)) {
            $detectedLayer = 1; // RajaOngkir
        } elseif (strpos($firstId, 'kp_') === 0) {
            $detectedLayer = 2; // Kodepos
        }
        
        // Display layer info
        $layerIcon = ['', '📡', '🌐', '💾'];
        $layerName = $layerStats[$detectedLayer]['name'];
        echo "\n{$layerIcon[$detectedLayer]} Data Source: Layer {$detectedLayer} - {$layerName}\n";
        
        // Check if matches expected layer
        if ($detectedLayer == $testCity['expected_layer']) {
            echo "✓ Correct layer used!\n";
        } else {
            echo "⚠️  Expected Layer {$testCity['expected_layer']}, got Layer {$detectedLayer}\n";
        }
        
        // Update stats
        $layerStats[$detectedLayer]['count']++;
        $layerStats[$detectedLayer]['cities'][] = $testCity['name'];
        
        // Show sample subdistricts
        echo "\nSample subdistricts (first 5):\n";
        $displayCount = 0;
        foreach ($subdistricts as $subdistrict) {
            if ($displayCount >= 5) {
                echo "   ... and " . ($count - 5) . " more\n";
                break;
            }
            
            $postalInfo = $subdistrict['postal_code'] ? " (Postal: {$subdistrict['postal_code']})" : "";
            echo "   " . ($displayCount + 1) . ". {$subdistrict['subdistrict_name']}{$postalInfo}\n";
            $displayCount++;
        }
        
        // Check expected count
        if (isset($testCity['expected_count']) && $testCity['expected_count']) {
            if ($count == $testCity['expected_count']) {
                echo "\n✓ Count matches expected: {$testCity['expected_count']}\n";
            } else {
                echo "\n⚠️  Expected {$testCity['expected_count']} subdistricts, got {$count}\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Display summary
echo str_repeat('=', 70) . "\n";
echo "SUMMARY - 3-Layer Fallback Strategy\n";
echo str_repeat('=', 70) . "\n\n";

foreach ($layerStats as $layer => $stats) {
    $icon = ['', '📡', '🌐', '💾'][$layer];
    $percentage = count($testCities) > 0 ? round(($stats['count'] / count($testCities)) * 100) : 0;
    
    echo "{$icon} Layer {$layer}: {$stats['name']}\n";
    echo "   Used: {$stats['count']} times ({$percentage}%)\n";
    
    if (!empty($stats['cities'])) {
        echo "   Cities: " . implode(', ', $stats['cities']) . "\n";
    }
    echo "\n";
}

// Final verdict
echo str_repeat('=', 70) . "\n";
$layer1Count = $layerStats[1]['count'];
$layer2Count = $layerStats[2]['count'];
$layer3Count = $layerStats[3]['count'];
$totalSuccess = $layer1Count + $layer2Count;

if ($totalSuccess == count($testCities)) {
    echo "🎉 PERFECT! All cities have real data (Layer 1 or 2)\n";
    echo "   - Layer 1 (RajaOngkir): {$layer1Count} cities\n";
    echo "   - Layer 2 (Kodepos): {$layer2Count} cities\n";
    echo "   - Layer 3 (Mock): {$layer3Count} cities\n";
    echo "\n✅ 100% Coverage with Real Data!\n";
} elseif ($totalSuccess > 0) {
    echo "⚠️  PARTIAL SUCCESS\n";
    echo "   - Real Data: {$totalSuccess} cities\n";
    echo "   - Mock Data: {$layer3Count} cities\n";
    echo "\n✓ Hybrid solution is working, but some cities using mock data\n";
} else {
    echo "❌ ALL TESTS USING MOCK DATA\n";
    echo "   Please check:\n";
    echo "   1. RajaOngkir API key in .env\n";
    echo "   2. Internet connection\n";
    echo "   3. API endpoints availability\n";
}

echo str_repeat('=', 70) . "\n";
