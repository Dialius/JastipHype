<?php

/**
 * Test Deduplication Scenarios
 * 
 * Simulates different scenarios to see which deduplication strategy works best
 */

echo "=== TESTING DEDUPLICATION SCENARIOS ===\n\n";

// Simulate data with various overlap scenarios
$rajaongkirData = [
    ['id' => '2001', 'name' => 'Andir', 'postal' => '', 'source' => 'rajaongkir'],
    ['id' => '2002', 'name' => 'Antapani', 'postal' => '', 'source' => 'rajaongkir'],
    ['id' => '2003', 'name' => 'Arcamanik', 'postal' => '', 'source' => 'rajaongkir'],
    ['id' => '2004', 'name' => 'Bandung Kidul', 'postal' => '', 'source' => 'rajaongkir'],
    ['id' => '2005', 'name' => 'Cicendo', 'postal' => '', 'source' => 'rajaongkir'],
];

$kodeposData = [
    // Exact match (same name)
    ['id' => 'kp_40181', 'name' => 'Andir', 'postal' => '40181', 'source' => 'kodepos'],
    ['id' => 'kp_40291', 'name' => 'Antapani', 'postal' => '40291', 'source' => 'kodepos'],
    
    // Similar name but different area (different postal)
    ['id' => 'kp_40293', 'name' => 'Arcamanik', 'postal' => '40293', 'source' => 'kodepos'],
    ['id' => 'kp_40294', 'name' => 'Arcamanik', 'postal' => '40294', 'source' => 'kodepos'], // Different postal!
    
    // New areas not in RajaOngkir
    ['id' => 'kp_40263', 'name' => 'Bandung Wetan', 'postal' => '40263', 'source' => 'kodepos'],
    ['id' => 'kp_40212', 'name' => 'Bandung Kulon', 'postal' => '40212', 'source' => 'kodepos'],
    ['id' => 'kp_40172', 'name' => 'Cicendo', 'postal' => '40172', 'source' => 'kodepos'],
    ['id' => 'kp_40173', 'name' => 'Cicendo', 'postal' => '40173', 'source' => 'kodepos'], // Same name, different postal!
];

echo "SIMULATED DATA:\n";
echo "  RajaOngkir: " . count($rajaongkirData) . " subdistricts\n";
echo "  Kodepos: " . count($kodeposData) . " subdistricts\n";
echo "  Total: " . (count($rajaongkirData) + count($kodeposData)) . " subdistricts\n\n";

$normalize = function($name) {
    return strtolower(trim(preg_replace('/\s+/', ' ', $name)));
};

// Strategy 1: Using POSTAL CODE as unique key
echo str_repeat('=', 70) . "\n";
echo "STRATEGY 1: Using POSTAL CODE as unique key\n";
echo str_repeat('=', 70) . "\n";
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

echo "Result: " . count($merged1) . " subdistricts\n\n";
echo "List:\n";
foreach ($merged1 as $i => $item) {
    $postal = $item['postal'] ?: 'no postal';
    echo sprintf("  %2d. %-20s (Postal: %-10s Source: %s)\n", 
        $i+1, $item['name'], $postal, $item['source']);
}
echo "\n";

// Strategy 2: Using NAME + POSTAL CODE as unique key
echo str_repeat('=', 70) . "\n";
echo "STRATEGY 2: Using NAME + POSTAL CODE as unique key\n";
echo str_repeat('=', 70) . "\n";
$merged2 = [];
$seen2 = [];

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

echo "Result: " . count($merged2) . " subdistricts\n\n";
echo "List:\n";
foreach ($merged2 as $i => $item) {
    $postal = $item['postal'] ?: 'no postal';
    echo sprintf("  %2d. %-20s (Postal: %-10s Source: %s)\n", 
        $i+1, $item['name'], $postal, $item['source']);
}
echo "\n";

// Strategy 3: Using NAME ONLY as unique key
echo str_repeat('=', 70) . "\n";
echo "STRATEGY 3: Using NAME ONLY as unique key\n";
echo str_repeat('=', 70) . "\n";
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

echo "Result: " . count($merged3) . " subdistricts\n\n";
echo "List:\n";
foreach ($merged3 as $i => $item) {
    $postal = $item['postal'] ?: 'no postal';
    echo sprintf("  %2d. %-20s (Postal: %-10s Source: %s)\n", 
        $i+1, $item['name'], $postal, $item['source']);
}
echo "\n";

// Strategy 4: NO DEDUPLICATION
echo str_repeat('=', 70) . "\n";
echo "STRATEGY 4: NO DEDUPLICATION (show all)\n";
echo str_repeat('=', 70) . "\n";
$merged4 = array_merge($rajaongkirData, $kodeposData);

echo "Result: " . count($merged4) . " subdistricts\n\n";
echo "List:\n";
foreach ($merged4 as $i => $item) {
    $postal = $item['postal'] ?: 'no postal';
    echo sprintf("  %2d. %-20s (Postal: %-10s Source: %s)\n", 
        $i+1, $item['name'], $postal, $item['source']);
}
echo "\n";

// Analysis
echo str_repeat('=', 70) . "\n";
echo "ANALYSIS\n";
echo str_repeat('=', 70) . "\n\n";

echo "Coverage Comparison:\n";
echo sprintf("  Strategy 1 (Postal Code):    %2d subdistricts\n", count($merged1));
echo sprintf("  Strategy 2 (Name + Postal):  %2d subdistricts\n", count($merged2));
echo sprintf("  Strategy 3 (Name Only):      %2d subdistricts\n", count($merged3));
echo sprintf("  Strategy 4 (No Dedup):       %2d subdistricts\n", count($merged4));
echo "\n";

echo "Key Observations:\n\n";

echo "1. POSTAL CODE Strategy:\n";
echo "   ✅ Keeps ALL unique postal codes\n";
echo "   ✅ Maximum coverage for different areas\n";
echo "   ⚠️  Might show RajaOngkir entries without postal code\n";
echo "   Result: " . count($merged1) . " entries\n\n";

echo "2. NAME + POSTAL Strategy:\n";
echo "   ✅ Removes exact duplicates (same name + same postal)\n";
echo "   ✅ Keeps variations (same name, different postal = different area)\n";
echo "   ✅ Good balance between coverage and deduplication\n";
echo "   Result: " . count($merged2) . " entries\n\n";

echo "3. NAME ONLY Strategy:\n";
echo "   ❌ Removes ALL duplicates by name\n";
echo "   ❌ Loses different areas with same name\n";
echo "   ❌ Lowest coverage\n";
echo "   Result: " . count($merged3) . " entries\n\n";

echo "4. NO DEDUPLICATION Strategy:\n";
echo "   ✅ Shows ALL data\n";
echo "   ❌ User sees duplicate names in dropdown\n";
echo "   ❌ Confusing user experience\n";
echo "   Result: " . count($merged4) . " entries\n\n";

echo str_repeat('=', 70) . "\n";
echo "RECOMMENDATION\n";
echo str_repeat('=', 70) . "\n\n";

if (count($merged1) == count($merged2)) {
    echo "✅ BEST: Strategy 2 (NAME + POSTAL CODE)\n\n";
    echo "Why?\n";
    echo "  • Same coverage as postal-only strategy\n";
    echo "  • Better handling of RajaOngkir data without postal\n";
    echo "  • Removes exact duplicates\n";
    echo "  • Keeps legitimate variations (same name, different postal)\n";
    echo "  • Best user experience\n\n";
} else if (count($merged1) > count($merged2)) {
    echo "✅ BEST: Strategy 1 (POSTAL CODE)\n\n";
    echo "Why?\n";
    echo "  • Maximum coverage (" . count($merged1) . " vs " . count($merged2) . ")\n";
    echo "  • Every unique postal code = unique area\n";
    echo "  • Best for comprehensive area coverage\n\n";
} else {
    echo "✅ BEST: Strategy 2 (NAME + POSTAL CODE)\n\n";
    echo "Why?\n";
    echo "  • Better coverage than postal-only\n";
    echo "  • Handles edge cases better\n\n";
}

echo "Example: Arcamanik appears twice with different postal codes:\n";
echo "  • Arcamanik (40293) - Area 1\n";
echo "  • Arcamanik (40294) - Area 2\n";
echo "  These are DIFFERENT areas and should BOTH be shown!\n\n";

echo "Example: Cicendo appears twice:\n";
echo "  • Cicendo (no postal) - from RajaOngkir\n";
echo "  • Cicendo (40172) - from Kodepos\n";
echo "  • Cicendo (40173) - from Kodepos (different area)\n";
echo "  Strategy 2 handles this best by enriching RajaOngkir data\n";
echo "  and keeping different postal codes.\n\n";
