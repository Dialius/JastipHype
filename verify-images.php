<?php

/**
 * Script to verify that images are accessible after migration
 * Run this after migrate-images-to-public.php
 * 
 * php verify-images.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Helpers\ImageHelper;

echo "===========================================\n";
echo "VERIFY IMAGE ACCESSIBILITY\n";
echo "===========================================\n\n";

$errors = [];
$warnings = [];
$success = 0;

// Test 1: Check if uploads folder exists
echo "1. Checking uploads folder...\n";
$uploadsPath = public_path('uploads');
if (file_exists($uploadsPath)) {
    echo "   ✅ public/uploads exists\n";
    $success++;
} else {
    echo "   ❌ public/uploads does NOT exist!\n";
    $errors[] = "public/uploads folder missing";
}

// Test 2: Check products with images
echo "\n2. Checking product images...\n";
$products = DB::table('product_images')->limit(5)->get();
foreach ($products as $image) {
    $fullPath = public_path('uploads/' . $image->image_path);
    $url = ImageHelper::getImageUrl($image->image_path);
    
    echo "   Product Image: {$image->image_path}\n";
    echo "   - File exists: " . (file_exists($fullPath) ? '✅ YES' : '❌ NO') . "\n";
    echo "   - Generated URL: $url\n";
    
    if (file_exists($fullPath)) {
        $success++;
    } else {
        $errors[] = "Missing: {$image->image_path}";
    }
}

// Test 3: Check categories with images
echo "\n3. Checking category images...\n";
$categories = DB::table('categories')->whereNotNull('image')->limit(5)->get();
foreach ($categories as $category) {
    $fullPath = public_path('uploads/' . $category->image);
    $url = ImageHelper::getCategoryImageUrl($category);
    
    echo "   Category: {$category->name}\n";
    echo "   - Image: {$category->image}\n";
    echo "   - File exists: " . (file_exists($fullPath) ? '✅ YES' : '❌ NO') . "\n";
    echo "   - Generated URL: $url\n";
    
    if (file_exists($fullPath)) {
        $success++;
    } else {
        $errors[] = "Missing: {$category->image}";
    }
}

// Test 4: Check brands with logos
echo "\n4. Checking brand logos...\n";
$brands = DB::table('brands')->whereNotNull('logo_path')->limit(5)->get();
foreach ($brands as $brand) {
    $fullPath = public_path('uploads/' . $brand->logo_path);
    $url = ImageHelper::getBrandLogoUrl($brand);
    
    echo "   Brand: {$brand->name}\n";
    echo "   - Logo: {$brand->logo_path}\n";
    echo "   - File exists: " . (file_exists($fullPath) ? '✅ YES' : '❌ NO') . "\n";
    echo "   - Generated URL: $url\n";
    
    if (file_exists($fullPath)) {
        $success++;
    } else {
        $errors[] = "Missing: {$brand->logo_path}";
    }
}

// Test 5: Check banners with images
echo "\n5. Checking banner images...\n";
$banners = DB::table('banners')->whereNotNull('image_path')->limit(5)->get();
foreach ($banners as $banner) {
    $fullPath = public_path('uploads/' . $banner->image_path);
    $url = ImageHelper::getBannerImageUrl($banner);
    
    echo "   Banner: {$banner->title}\n";
    echo "   - Image: {$banner->image_path}\n";
    echo "   - File exists: " . (file_exists($fullPath) ? '✅ YES' : '❌ NO') . "\n";
    echo "   - Generated URL: $url\n";
    
    if (file_exists($fullPath)) {
        $success++;
    } else {
        $errors[] = "Missing: {$banner->image_path}";
    }
}

// Test 6: Check URL format
echo "\n6. Checking URL format...\n";
$testPath = 'products/test.jpg';
$testUrl = ImageHelper::getImageUrl($testPath);
$expectedUrl = url('uploads/products/test.jpg');

echo "   Test path: $testPath\n";
echo "   Generated URL: $testUrl\n";
echo "   Expected URL: $expectedUrl\n";

if ($testUrl === $expectedUrl) {
    echo "   ✅ URL format is correct\n";
    $success++;
} else {
    echo "   ❌ URL format is INCORRECT\n";
    $errors[] = "URL format mismatch";
}

// Summary
echo "\n===========================================\n";
echo "VERIFICATION SUMMARY\n";
echo "===========================================\n";
echo "Successful checks: $success\n";
echo "Errors: " . count($errors) . "\n";
echo "Warnings: " . count($warnings) . "\n\n";

if (count($errors) > 0) {
    echo "❌ ERRORS FOUND:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "⚠️  WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "   - $warning\n";
    }
    echo "\n";
}

if (count($errors) === 0) {
    echo "✅ ALL CHECKS PASSED!\n";
    echo "\n📝 NEXT STEPS:\n";
    echo "1. Start your development server: php artisan serve\n";
    echo "2. Open your website in browser\n";
    echo "3. Check if images are loading correctly\n";
    echo "4. If everything works, commit and push to GitHub\n";
    exit(0);
} else {
    echo "❌ SOME CHECKS FAILED!\n";
    echo "\n📝 TROUBLESHOOTING:\n";
    echo "1. Make sure you ran: php migrate-images-to-public.php\n";
    echo "2. Check if files exist in storage/app/public\n";
    echo "3. Check file permissions\n";
    exit(1);
}
