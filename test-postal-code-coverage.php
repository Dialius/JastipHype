<?php

/**
 * Test Postal Code Coverage
 * 
 * This script tests different merging strategies:
 * 1. Using postal code as unique key
 * 2. Using name + postal code as unique key
 * 3. Using name only as unique key
 * 
 * To see which gives maximum coverage
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Cache;

echo "=== TESTING POSTAL CODE COVERAGE ===\n\n";

$service = new RajaOngkirService();

// Test city
$testCity = ['id' => '22', 'name' => 'Kota Bandung'];

echo "Testing: {$testCity['name']} (ID: {$testCity['id']})\n";
echo str_repeat('=', 70) . "\n\n";

// Clear cache
Cache::forget("rajaongkir_subdistricts_{$testCity['id']}");

// Get raw data from both APIs
$rajaongkirData = [];
$kodeposData = [];

// Fetch RajaOngkir
try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'key' => config('rajaongkir.api_key')
    ])->get("https://rajaongkir.komerce.id/api/v1/location/subdistrict", [
        'city' => $testCity['id']
    ]);

    if ($response->successful()) {
        $data = $response->json();
        if (isset($data['rajaongkir']['results'])) {
            foreach ($data['rajaongkir']['results'] as $item) {
                $rajaongkirData[] = [
                    'id' => $item['subdistrict_id'],
                    'name' => $item['subdistrict_name'],
                    'postal' => '',
                    'source' => 'rajaongkir'
                ];
            }
        }
    }
} catch (Exception $e) {
    echo "RajaOngkir API Error: " . $e->getMessage() . "\n";
}

// Fetch Kodepos
try {
    $response = \Illuminate\Support\Facades\Http::timeout(5)
        ->get("https://kodepos.vercel.app/search/", ['q' => 'Bandung']);

    if ($response->successful()) {
        $data = $response->json();
        if (isset($data['data'])) {
            $seen = [];
            foreach ($data['data'] as $item) {
                $districtName = $item['district'] ?? '';
                if ($districtName && !isset($seen[$districtName])) {
                    $kodeposData[] = [
                        'id' => 'kp_' . $item['code'],
                        'name' => $districtName,
                        'postal' => $item['code'],
                        'source' => 'kodepos'
                    ];
                    $seen[$districtName] = true;
                }
            }
        }
    }
} catch (Exception $e) {
    echo "Kodepos API Error: " . $e->getMessage() . "\n";
}

echo "RAW DATA:\n";
echo "  RajaOngkir: " . count($rajaongkirData) . " subdistricts\n";
echo "  Kodepos: " . count($kodeposData) . " subdistricts\n";
echo "  Total: " . (count($rajaongkirData) + count($kodeposData)) . " subdistricts\n\n";

// Show sample data
echo "Sample RajaOngkir data (first 5):\n";
foreach (array_slice($rajaongkirData, 0, 5) as $i => $item) {
    echo "  " . ($i+1) . ". {$item['name']} (ID: {$item['id']}, Postal: " . ($item['postal'] ?: 'empty') . ")\n";
}
echo "\n";

echo "Sample Kodepos data (first 5):\n";
foreach (array_slice($kodeposData, 0, 5) as $i => $item) {
    echo "  " . ($i+1) . ". {$item['name']} (ID: {$item['id']}, Postal: {$item['postal']})\n";
}
echo "\n";

// Test different merging strategies
echo str_repeat('=', 70) . "\n";
echo "TESTING DIFFERENT MERGING STRATEGIES\n";
echo str_repeat('=', 70) . "\n\n";

// Strategy 1: Using POSTAL CODE as unique key
echo "STRATEGY 1: Using POSTAL CODE as unique key\n";
echo str_repeat('-', 70) . "\n";
$merged1 = [];
$seen1 = [];

foreach ($rajaongkirData as $item) {
    $key = $item['postal'] ?: 'empty_' . $item['id'];
    if (!isset($seen1[$key])) {
        $merged1[] = $item;
        $seen1[$key] = true;
    }
}

foreach ($kodeposData as $item) {
    $key = $item['postal'];
    if (!isset($seen1[$key])) {
        $merged1[] = $item;
        $seen1[$key] = true;
    }
}

echo "Result: " . count($merged1) . " subdistricts\n";
echo "  From RajaOngkir: " . count(array_filter($merged1, fn($x) => $x['source'] == 'rajaongkir')) . "\n";
echo "  From Kodepos: " . count(array_filter($merged1, fn($x) => $x['source'] == 'kodepos')) . "\n";
echo "  Duplicates removed: " . ((count($rajaongkirData) + count($kodeposData)) - count($merged1)) . "\n\n";

// Strategy 2: Using NAME + POSTAL CODE as unique key
echo "STRATEGY 2: Using NAME + POSTAL CODE as unique key\n";
echo str_repeat('-', 70) . "\n";
$merged2 = [];
$seen2 = [];

$normalize = function($name) {
    return strtolower(trim(preg_replace('/\s+/', ' ', $name)));
};

foreach ($rajaongkirData as $item) {
    $key = $normalize($item['name']) . '|' . ($item['postal'] ?: 'empty');
    if (!isset($seen2[$key])) {
        $merged2[] = $item;
        $seen2[$key] = true;
    }
}

foreach ($kodeposData as $item) {
    $key = $normalize($item['name']) . '|' . $item['postal'];
    if (!isset($seen2[$key])) {
        $merged2[] = $item;
        $seen2[$key] = true;
    }
}

echo "Result: " . count($merged2) . " subdistricts\n";
echo "  From RajaOngkir: " . count(array_filter($merged2, fn($x) => $x['source'] == 'rajaongkir')) . "\n";
echo "  From Kodepos: " . count(array_filter($merged2, fn($x) => $x['source'] == 'kodepos')) . "\n";
echo "  Duplicates removed: " . ((count($rajaongkirData) + count($kodeposData)) - count($merged2)) . "\n\n";

// Strategy 3: Using NAME ONLY as unique key
echo "STRATEGY 3: Using NAME ONLY as unique key\n";
echo str_repeat('-', 70) . "\n";
$merged3 = [];
$seen3 = [];

foreach ($rajaongkirData as $item) {
    $key = $normalize($item['name']);
    if (!isset($seen3[$key])) {
        $merged3[] = $item;
        $seen3[$key] = true;
    }
}

foreach ($kodeposData as $item) {
    $key = $normalize($item['name']);
    if (!isset($seen3[$key])) {
        $merged3[] = $item;
        $seen3[$key] = true;
    }
}

echo "Result: " . count($merged3) . " subdistricts\n";
echo "  From RajaOngkir: " . count(array_filter($merged3, fn($x) => $x['source'] == 'rajaongkir')) . "\n";
echo "  From Kodepos: " . count(array_filter($merged3, fn($x) => $x['source'] == 'kodepos')) . "\n";
echo "  Duplicates removed: " . ((count($rajaongkirData) + count($kodeposData)) - count($merged3)) . "\n\n";

// Strategy 4: NO DEDUPLICATION (show all)
echo "STRATEGY 4: NO DEDUPLICATION (show all data)\n";
echo str_repeat('-', 70) . "\n";
$merged4 = array_merge($rajaongkirData, $kodeposData);

echo "Result: " . count($merged4) . " subdistricts\n";
echo "  From RajaOngkir: " . count($rajaongkirData) . "\n";
echo "  From Kodepos: " . count($kodeposData) . "\n";
echo "  Duplicates removed: 0 (showing all)\n\n";

// Comparison
echo str_repeat('=', 70) . "\n";
echo "COMPARISON\n";
echo str_repeat('=', 70) . "\n\n";

$strategies = [
    'Strategy 1 (Postal Code)' => count($merged1),
    'Strategy 2 (Name + Postal)' => count($merged2),
    'Strategy 3 (Name Only)' => count($merged3),
    'Strategy 4 (No Dedup)' => count($merged4),
];

arsort($strategies);

foreach ($strategies as $name => $count) {
    $percentage = round(($count / count($merged4)) * 100, 1);
    echo sprintf("%-30s: %3d subdistricts (%5.1f%%)\n", $name, $count, $percentage);
}

echo "\n";

// Recommendation
$maxCount = max($strategies);
$recommended = array_search($maxCount, $strategies);

echo "RECOMMENDATION:\n";
echo "  Best strategy: {$recommended}\n";
echo "  Coverage: {$maxCount} subdistricts\n\n";

if ($recommended == 'Strategy 4 (No Dedup)') {
    echo "  ⚠️  WARNING: This shows ALL data including duplicates!\n";
    echo "  User might see duplicate entries in the dropdown.\n\n";
}

// Check for potential duplicates in best strategy
if ($recommended == 'Strategy 2 (Name + Postal)') {
    echo "  ✅ GOOD: This removes exact duplicates while keeping variations.\n";
    echo "  Same name with different postal codes = different areas (kept).\n\n";
}

echo str_repeat('=', 70) . "\n";
echo "TEST COMPLETE\n";
echo str_repeat('=', 70) . "\n";
