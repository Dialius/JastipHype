<?php

/**
 * COMPREHENSIVE IMAGE SYSTEM TEST
 * 
 * This script tests ALL aspects of the image system:
 * 1. Configuration
 * 2. File existence
 * 3. URL generation
 * 4. Database paths
 * 5. Helper functions
 * 6. Upload functionality
 * 
 * Run: php test-complete-image-system.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\ImageHelper;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     COMPREHENSIVE IMAGE SYSTEM TEST - JastipHype             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

$totalTests = 0;
$passedTests = 0;
$failedTests = 0;
$warnings = [];
$errors = [];

// ============================================================================
// TEST 1: CONFIGURATION
// ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 1: CONFIGURATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$totalTests++;
$defaultDisk = config('filesystems.default');
echo "Default disk: $defaultDisk\n";
if ($defaultDisk === 'public') {
    echo "✅ PASS: Default disk is 'public'\n";
    $passedTests++;
} else {
    echo "❌ FAIL: Default disk should be 'public', got '$defaultDisk'\n";
    $failedTests++;
    $errors[] = "Wrong default disk: $defaultDisk";
}

$totalTests++;
$publicDiskRoot = config('filesystems.disks.public.root');
$expectedRoot = public_path('uploads');
echo "\nPublic disk root: $publicDiskRoot\n";
echo "Expected: $expectedRoot\n";
if ($publicDiskRoot === $expectedRoot) {
    echo "✅ PASS: Public disk root is correct\n";
    $passedTests++;
} else {
    echo "❌ FAIL: Public disk root is wrong\n";
    $failedTests++;
    $errors[] = "Wrong public disk root: $publicDiskRoot";
}

$totalTests++;
$publicDiskUrl = config('filesystems.disks.public.url');
$expectedUrl = env('APP_URL').'/uploads';
echo "\nPublic disk URL: $publicDiskUrl\n";
echo "Expected: $expectedUrl\n";
if ($publicDiskUrl === $expectedUrl) {
    echo "✅ PASS: Public disk URL is correct\n";
    $passedTests++;
} else {
    echo "❌ FAIL: Public disk URL is wrong\n";
    $failedTests++;
    $errors[] = "Wrong public disk URL: $publicDiskUrl";
}

// ============================================================================
// TEST 2: FOLDER STRUCTURE
// ============================================================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 2: FOLDER STRUCTURE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$folders = ['products', 'categories', 'brands', 'banners'];
foreach ($folders as $folder) {
    $totalTests++;
    $path = public_path("uploads/$folder");
    echo "Checking: $path\n";
    if (File::exists($path)) {
        echo "✅ PASS: Folder exists\n";
        $passedTests++;
        
        // Count files
        $files = File::allFiles($path);
        echo "   Files: " . count($files) . "\n";
    } else {
        echo "⚠️  WARN: Folder does not exist (will be created on first upload)\n";
        $warnings[] = "Folder missing: $folder";
    }
    echo "\n";
}

// ============================================================================
// TEST 3: DATABASE PATHS
// ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 3: DATABASE PATHS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Check product images
echo "Checking product_images table...\n";
$productImages = DB::table('product_images')->limit(5)->get();
foreach ($productImages as $image) {
    $totalTests++;
    echo "\nProduct Image ID: {$image->id}\n";
    echo "Path in DB: {$image->image_path}\n";
    
    // Check if path is relative (good)
    if (!str_starts_with($image->image_path, '/') && !str_starts_with($image->image_path, 'http')) {
        echo "✅ Path is relative (good)\n";
        $passedTests++;
    } else {
        echo "❌ Path is absolute (bad)\n";
        $failedTests++;
        $errors[] = "Absolute path in product_images: {$image->image_path}";
    }
    
    // Check if file exists
    $fullPath = public_path('uploads/' . $image->image_path);
    if (File::exists($fullPath)) {
        echo "✅ File exists: $fullPath\n";
    } else {
        echo "❌ File NOT found: $fullPath\n";
        $errors[] = "Missing file: {$image->image_path}";
    }
}

// Check categories
echo "\n\nChecking categories table...\n";
$categories = DB::table('categories')->whereNotNull('image')->limit(5)->get();
foreach ($categories as $category) {
    $totalTests++;
    echo "\nCategory: {$category->name}\n";
    echo "Path in DB: {$category->image}\n";
    
    if (!str_starts_with($category->image, '/') && !str_starts_with($category->image, 'http')) {
        echo "✅ Path is relative (good)\n";
        $passedTests++;
    } else {
        echo "❌ Path is absolute (bad)\n";
        $failedTests++;
        $errors[] = "Absolute path in categories: {$category->image}";
    }
    
    $fullPath = public_path('uploads/' . $category->image);
    if (File::exists($fullPath)) {
        echo "✅ File exists: $fullPath\n";
    } else {
        echo "❌ File NOT found: $fullPath\n";
        $errors[] = "Missing file: {$category->image}";
    }
}

// Check brands
echo "\n\nChecking brands table...\n";
$brands = DB::table('brands')->whereNotNull('logo_path')->limit(5)->get();
foreach ($brands as $brand) {
    $totalTests++;
    echo "\nBrand: {$brand->name}\n";
    echo "Path in DB: {$brand->logo_path}\n";
    
    if (!str_starts_with($brand->logo_path, '/') && !str_starts_with($brand->logo_path, 'http')) {
        echo "✅ Path is relative (good)\n";
        $passedTests++;
    } else {
        echo "❌ Path is absolute (bad)\n";
        $failedTests++;
        $errors[] = "Absolute path in brands: {$brand->logo_path}";
    }
    
    $fullPath = public_path('uploads/' . $brand->logo_path);
    if (File::exists($fullPath)) {
        echo "✅ File exists: $fullPath\n";
    } else {
        echo "❌ File NOT found: $fullPath\n";
        $errors[] = "Missing file: {$brand->logo_path}";
    }
}

// Check banners
echo "\n\nChecking banners table...\n";
$banners = DB::table('banners')->whereNotNull('image_path')->limit(5)->get();
foreach ($banners as $banner) {
    $totalTests++;
    echo "\nBanner: {$banner->title}\n";
    echo "Path in DB: {$banner->image_path}\n";
    
    if (!str_starts_with($banner->image_path, '/') && !str_starts_with($banner->image_path, 'http')) {
        echo "✅ Path is relative (good)\n";
        $passedTests++;
    } else {
        echo "❌ Path is absolute (bad)\n";
        $failedTests++;
        $errors[] = "Absolute path in banners: {$banner->image_path}";
    }
    
    $fullPath = public_path('uploads/' . $banner->image_path);
    if (File::exists($fullPath)) {
        echo "✅ File exists: $fullPath\n";
    } else {
        echo "❌ File NOT found: $fullPath\n";
        $errors[] = "Missing file: {$banner->image_path}";
    }
}

// ============================================================================
// TEST 4: URL GENERATION
// ============================================================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 4: URL GENERATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$totalTests++;
$testPath = 'products/test-image.jpg';
$generatedUrl = ImageHelper::getImageUrl($testPath);
$expectedUrl = url('uploads/products/test-image.jpg');

echo "Test path: $testPath\n";
echo "Generated URL: $generatedUrl\n";
echo "Expected URL: $expectedUrl\n";

if ($generatedUrl === $expectedUrl) {
    echo "✅ PASS: URL generation is correct\n";
    $passedTests++;
} else {
    echo "❌ FAIL: URL generation is wrong\n";
    $failedTests++;
    $errors[] = "Wrong URL generation";
}

// ============================================================================
// TEST 5: HELPER FUNCTIONS
// ============================================================================
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TEST 5: HELPER FUNCTIONS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$totalTests++;
try {
    $url = image_url('test.jpg');
    echo "image_url('test.jpg'): $url\n";
    if (str_contains($url, '/uploads/')) {
        echo "✅ PASS: image_url() works and uses /uploads/\n";
        $passedTests++;
    } else {
        echo "❌ FAIL: image_url() doesn't use /uploads/\n";
        $failedTests++;
        $errors[] = "image_url() wrong path";
    }
} catch (\Exception $e) {
    echo "❌ FAIL: image_url() threw exception: " . $e->getMessage() . "\n";
    $failedTests++;
    $errors[] = "image_url() exception";
}

// ============================================================================
// SUMMARY
// ============================================================================
echo "\n\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                        TEST SUMMARY                          ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "Total Tests: $totalTests\n";
echo "✅ Passed: $passedTests\n";
echo "❌ Failed: $failedTests\n";
echo "⚠️  Warnings: " . count($warnings) . "\n";
echo "\n";

if ($failedTests > 0) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ERRORS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($errors as $error) {
        echo "❌ $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "WARNINGS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($warnings as $warning) {
        echo "⚠️  $warning\n";
    }
    echo "\n";
}

$percentage = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0;
echo "Success Rate: $percentage%\n";
echo "\n";

if ($failedTests === 0) {
    echo "🎉 ALL TESTS PASSED! Image system is working correctly.\n";
    echo "\n";
    echo "Next steps:\n";
    echo "1. Test image upload in admin panel\n";
    echo "2. Check if images display on website\n";
    echo "3. Deploy to production\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED! Please fix the errors above.\n";
    echo "\n";
    echo "Common fixes:\n";
    echo "1. Run: php migrate-images-to-public.php\n";
    echo "2. Check config/filesystems.php\n";
    echo "3. Check app/Helpers/ImageHelper.php\n";
    echo "4. Clear cache: php artisan config:clear\n";
    exit(1);
}
