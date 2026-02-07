<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== DEBUG BANNER IMAGES ===\n\n";

// Check if database is connected
try {
    DB::connection()->getPdo();
    echo "✓ Database connected\n\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Get banners from database
echo "=== BANNERS IN DATABASE ===\n";
$banners = DB::table('banners')->get();

if ($banners->isEmpty()) {
    echo "No banners found in database\n";
} else {
    echo "Found " . $banners->count() . " banners:\n\n";
    
    foreach ($banners as $banner) {
        echo "Banner ID: {$banner->id}\n";
        echo "  Title: {$banner->title}\n";
        echo "  Type: {$banner->type}\n";
        echo "  Image Path: " . ($banner->image_path ?? 'NULL') . "\n";
        echo "  Product ID: " . ($banner->product_id ?? 'NULL') . "\n";
        echo "  Is Active: " . ($banner->is_active ? 'Yes' : 'No') . "\n";
        
        // Generate URL using helper
        if (function_exists('banner_image_url')) {
      