<?php
/**
 * Simple RajaOngkir API Test Script
 * 
 * Usage: php test-rajaongkir.php
 * 
 * This script tests the RajaOngkir API connection directly
 * to help troubleshoot any issues with the shipping calculator.
 */

// Load environment variables
require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['RAJAONGKIR_API_KEY'] ?? null;
$type = $_ENV['RAJAONGKIR_TYPE'] ?? 'starter';
$baseUrl = "https://api.rajaongkir.com/{$type}";

echo "=================================\n";
echo "RajaOngkir API Test\n";
echo "=================================\n\n";

if (!$apiKey) {
    echo "❌ ERROR: RAJAONGKIR_API_KEY not found in .env file\n";
    exit(1);
}

echo "✓ API Key: " . substr($apiKey, 0, 10) . "...\n";
echo "✓ Type: {$type}\n";
echo "✓ Base URL: {$baseUrl}\n\n";

// Test 1: Get Provinces
echo "Test 1: Fetching Provinces...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/province");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "key: {$apiKey}"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if (isset($data['rajaongkir']['results'])) {
        $count = count($data['rajaongkir']['results']);
        echo "✓ SUCCESS: Found {$count} provinces\n";
        echo "  Sample: " . $data['rajaongkir']['results'][0]['province'] . "\n\n";
    } else {
        echo "❌ ERROR: Invalid response format\n";
        echo "Response: " . substr($response, 0, 200) . "...\n\n";
    }
} else {
    echo "❌ ERROR: HTTP {$httpCode}\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
}

// Test 2: Get Cities (Jakarta - province_id = 6)
echo "Test 2: Fetching Cities for DKI Jakarta (province_id=6)...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/city?province=6");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "key: {$apiKey}"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if (isset($data['rajaongkir']['results'])) {
        $count = count($data['rajaongkir']['results']);
        echo "✓ SUCCESS: Found {$count} cities\n";
        echo "  Sample: " . $data['rajaongkir']['results'][0]['type'] . " " . $data['rajaongkir']['results'][0]['city_name'] . "\n\n";
    } else {
        echo "❌ ERROR: Invalid response format\n";
        echo "Response: " . substr($response, 0, 200) . "...\n\n";
    }
} else {
    echo "❌ ERROR: HTTP {$httpCode}\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
}

// Test 3: Calculate Shipping Cost (Jakarta Barat to Jakarta Selatan)
echo "Test 3: Calculating Shipping Cost (Jakarta Barat -> Jakarta Selatan, 1kg, JNE)...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/cost");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'origin' => 151,      // Jakarta Barat
    'destination' => 153, // Jakarta Selatan
    'weight' => 1000,     // 1kg
    'courier' => 'jne'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "key: {$apiKey}"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if (isset($data['rajaongkir']['results'][0]['costs'])) {
        $courier = $data['rajaongkir']['results'][0];
        echo "✓ SUCCESS: Shipping costs calculated\n";
        echo "  Courier: " . $courier['name'] . "\n";
        foreach ($courier['costs'] as $cost) {
            echo "  - {$cost['service']}: Rp " . number_format($cost['cost'][0]['value'], 0, ',', '.') . " ({$cost['cost'][0]['etd']} days)\n";
        }
        echo "\n";
    } else {
        echo "❌ ERROR: Invalid response format\n";
        echo "Response: " . substr($response, 0, 200) . "...\n\n";
    }
} else {
    echo "❌ ERROR: HTTP {$httpCode}\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
}

echo "=================================\n";
echo "Test Complete!\n";
echo "=================================\n";
