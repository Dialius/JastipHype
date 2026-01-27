<?php

/**
 * Debug Kodepos API Search
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Http;

echo "=== DEBUGGING KODEPOS API SEARCH ===\n\n";

$service = new RajaOngkirService();

// Get city info for Bandung
$cities = $service->getCities();
$bandungCity = null;
foreach ($cities as $city) {
    if ($city['city_id'] == '22') {
        $bandungCity = $city;
        break;
    }
}

if ($bandungCity) {
    echo "City Info:\n";
    echo "  ID: {$bandungCity['city_id']}\n";
    echo "  Type: {$bandungCity['type']}\n";
    echo "  Name: {$bandungCity['city_name']}\n";
    echo "  Full: {$bandungCity['type']} {$bandungCity['city_name']}\n\n";
    
    // Test normalization
    $cityName = $bandungCity['city_name'];
    $normalized = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $cityName);
    $normalized = trim($normalized);
    
    echo "Normalized Name: {$normalized}\n\n";
    
    // Test API call
    echo "Testing Kodepos API with query: {$normalized}\n";
    echo str_repeat('-', 60) . "\n";
    
    try {
        $response = Http::timeout(5)->get("https://kodepos.vercel.app/search/", [
            'q' => $normalized
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            
            echo "API Response Status: {$data['status']}\n";
            echo "Total Results: " . count($data['data']) . "\n\n";
            
            // Show first 10 results
            echo "First 10 results:\n";
            $count = 0;
            foreach ($data['data'] as $item) {
                if ($count >= 10) break;
                
                echo "  {$count}. District: {$item['district']}, Regency: {$item['regency']}, Province: {$item['province']}, Code: {$item['code']}\n";
                $count++;
            }
            
            // Group by district
            echo "\n\nGrouping by district:\n";
            $districts = [];
            $seen = [];
            
            foreach ($data['data'] as $item) {
                $districtName = $item['district'] ?? '';
                $regencyName = $item['regency'] ?? '';
                
                if ($districtName && !isset($seen[$districtName])) {
                    $districts[] = [
                        'district' => $districtName,
                        'regency' => $regencyName,
                        'postal_code' => $item['code']
                    ];
                    $seen[$districtName] = true;
                }
            }
            
            echo "Unique districts found: " . count($districts) . "\n";
            foreach ($districts as $d) {
                echo "  - {$d['district']} (Regency: {$d['regency']}, Postal: {$d['postal_code']})\n";
            }
            
        } else {
            echo "API Error: " . $response->status() . "\n";
        }
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }
}

echo "\n=== DEBUG COMPLETE ===\n";
