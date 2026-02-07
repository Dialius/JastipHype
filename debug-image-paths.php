<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Category;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Bootstrap the application for console usage
$app->make(Kernel::class)->bootstrap();

echo "Checking Category Images...\n==========================\n";

$categories = Category::whereIn('slug', ['accessories', 'clothing', 'hoodies', 'sneakers'])->get();

if ($categories->isEmpty()) {
    echo "No categories found with slugs: accessories, clothing, hoodies, sneakers\n";
}

foreach ($categories as $category) {
    echo "Category: {$category->name} (ID: {$category->id})\n";
    echo "DB Image Path: " . ($category->image ?? 'NULL') . "\n";
    
    $url = ImageHelper::getCategoryImageUrl($category);
    echo "Generated URL: {$url}\n";
    
    if ($category->image) {
        // Check if file exists in storage/app/public
        $existsStorage = Storage::disk('public')->exists($category->image);
        echo "Exists in 'public' disk (via Storage facade): " . ($existsStorage ? 'YES' : 'NO') . "\n";
        
        // Check physical path
        try {
            $fullPath = storage_path('app/public/' . $category->image);
            echo "Full System Path: {$fullPath}\n";
            echo "File Exists at Path: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
            
            // Check public link path
            $publicLinkPath = public_path('storage/' . $category->image);
            echo "Public Link Path: {$publicLinkPath}\n";
            echo "Link Exists: " . (file_exists($publicLinkPath) ? 'YES' : 'NO') . "\n";
            
        } catch (\Exception $e) {
            echo "Error checking paths: " . $e->getMessage() . "\n";
        }
    } else {
        echo "No image associated.\n";
    }
    
    echo "-----------------------------------\n";
}

echo "\nChecking Banners...\n===================\n";
$banners = \App\Models\Banner::all();
if ($banners->isEmpty()) {
    echo "No banners found.\n";
}

foreach ($banners as $banner) {
    echo "Banner: {$banner->title} (ID: {$banner->id})\n";
    echo "DB Image Path: " . ($banner->image_path ?? 'NULL') . "\n";
    
    $url = ImageHelper::getBannerImageUrl($banner);
    echo "Generated URL: {$url}\n";

    if ($banner->image_path) {
         $existsPublic = Storage::disk('public')->exists($banner->image_path);
        echo "Exists in 'public' disk: " . ($existsPublic ? 'YES' : 'NO') . "\n";
    }
    echo "-----------------------------------\n";
}
