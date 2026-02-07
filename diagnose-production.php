<?php

/**
 * PRODUCTION DIAGNOSTIC SCRIPT
 * 
 * Upload this file to production and run:
 * php diagnose-production.php
 * 
 * Or access via browser:
 * https://jastiphype.shop/diagnose-production.php
 */

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

header('Content-Type: text/plain; charset=utf-8');

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          PRODUCTION IMAGE DIAGNOSTIC - JastipHype            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$issues = [];
$fixes = [];

// ============================================================================
// CHECK 1: CONFIGURATION
// ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CHECK 1: CONFIGURATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$defaultDisk = config('filesystems.default');
$publicRoot = config('filesystems.disks.public.root');
$publicUrl = config('filesystems.disks.public.url');

echo "Default Disk: $defaultDisk\n";
echo "Public Root: $publicRoot\n";
echo "Public URL: $publicUrl\n";
echo "APP_URL: " . env('APP_URL') . "\n\n";

if ($defaultDisk !== 'public') {
    $issues[] = "Default disk is '$defaultDisk', should be 'public'";
    $fixes[] = "Update config/filesystems.php: 'default' => 'public'";
}

if (!str_contains($publicRoot, 'public/uploads') && !str_contains($publicRoot, 'public_html/uploads')) {
    $issues[] = "Public root doesn't point to uploads folder: $publicRoot";
    $fixes[] = "Update config/filesystems.php: 'root' => public_path('uploads')";
}

// ============================================================================
// CHECK 2: FOLDER STRUCTURE
// ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CHECK 2: FOLDER STRUCTURE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$folders = [
    'public/uploads' => public_path('uploads'),
    'public_html/uploads' => base_path('public_html/uploads'),
    'storage/app/public' => storage_path('app/public'),
];

foreach ($folders as $name => $path) {
    echo "Checking: $name\n";
    echo "Path: $path\n";
    
    if (File::exists($path)) {
        echo "✅ EXISTS\n";
        
        // Count files
        $subfolders = ['products', 'categories', 'brands', 'banners'];
        foreach ($subfolders as $subfolder) {
            $subpath = $path . '/' . $subfolder;
            if (File::exists($subpath)) {
                $files = File::allFiles($subpath);
                echo "   - $subfolder: " . count($files) . " files\n";
            }
        }
    } else {
        echo "❌ NOT FOUND\n";
        if ($name === 'public_html/uploads') {
            $issues[] = "public_html/uploads folder not found";
            $fixes[] = "Create folder: mkdir -p public_html/uploads";
        }
    }
    echo "\n";
}

// ============================================================================
// CHECK 3: DATABASE PATHS
// ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CHECK 3: DATABASE PATHS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

try {
    // Product Images
    echo "Product Images:\n";
    $productImages = DB::table('product_images')->limit(5)->get(['id', 'image_path']);
    foreach ($productImages as $img) {
        echo "  ID {$img->id}: {$img->image_path}\n";
        
        // Check if file exists
        $checkPaths = [
            public_path('uploads/' . $img->image_path),
            base_path('public_html/uploads/' . $img->image_path),
            storage_path('app/public/' . $img->image_path),
        ];
        
        $found = false;
        foreach ($checkPaths as $checkPath) {
            if (File::exists($checkPath)) {
                echo "    ✅ Found at: $checkPath\n";
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            echo "    ❌ File NOT FOUND in any location!\n";
            $issues[] = "Image file not found: {$img->image_path}";
        }
    }
    echo "\n";
    
    // Categories
    echo "Categories:\n";
    $categories = DB::table('categories')->whereNotNull('image')->limit(3)->get(['id', 'name', 'image']);
    foreach ($categories as $cat) {
        echo "  {$cat->name}: {$cat->image}\n";
        
        $checkPaths = [
            public_path('uploads/' . $cat->image),
            base_path('public_html/uploads/' . $cat->image),
            storage_path('app/public/' . $cat->image),
        ];
        
        $found = false;
        foreach ($checkPaths as $checkPath) {
            if (File::exists($checkPath)) {
                echo "    ✅ Found at: $checkPath\n";
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            echo "    ❌ File NOT FOUND\n";
        }
    }
    echo "\n";
    
    // Brands
    echo "Brands:\n";
    $brands = DB::table('brands')->whereNotNull('logo_path')->limit(3)->get(['id', 'name', 'logo_path']);
    foreach ($brands as $brand) {
        echo "  {$brand->name}: {$brand->logo_path}\n";
        
        $checkPaths = [
            public_path('uploads/' . $brand->logo_path),
            base_path('public_html/uploads/' . $brand->logo_path),
            storage_path('app/public/' . $brand->logo_path),
        ];
        
        $found = false;
        foreach ($checkPaths as $checkPath) {
            if (File::exists($checkPath)) {
                echo "    ✅ Found at: $checkPath\n";
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            echo "    ❌ File NOT FOUND\n";
        }
    }
    echo "\n";
    
} catch (\Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n\n";
    $issues[] = "Cannot connect to database";
    $fixes[] = "Check .env database credentials";
}

// ============================================================================
// CHECK 4: URL GENERATION
// ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CHECK 4: URL GENERATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$testPath = 'products/test.jpg';
$expectedUrl = url('uploads/products/test.jpg');

echo "Test Path: $testPath\n";
echo "Expected URL: $expectedUrl\n";

try {
    $generatedUrl = \App\Helpers\ImageHelper::getImageUrl($testPath);
    echo "Generated URL: $generatedUrl\n\n";
    
    if ($generatedUrl !== $expectedUrl) {
        $issues[] = "URL generation mismatch";
        $fixes[] = "Check app/Helpers/ImageHelper.php";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
    $issues[] = "ImageHelper error: " . $e->getMessage();
}

// ============================================================================
// CHECK 5: MIGRATION SCRIPT
// ============================================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CHECK 5: MIGRATION SCRIPT\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if (File::exists(base_path('migrate-images-to-public.php'))) {
    echo "✅ migrate-images-to-public.php EXISTS\n\n";
} else {
    echo "❌ migrate-images-to-public.php NOT FOUND\n\n";
    $issues[] = "Migration script not found";
    $fixes[] = "Upload migrate-images-to-public.php to server";
}

// ============================================================================
// SUMMARY
// ============================================================================
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                          SUMMARY                             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

if (count($issues) === 0) {
    echo "🎉 NO ISSUES FOUND!\n\n";
    echo "If images still not showing:\n";
    echo "1. Clear browser cache\n";
    echo "2. Check browser console for errors\n";
    echo "3. Test direct URL: " . url('uploads/products/[filename].jpg') . "\n";
} else {
    echo "❌ FOUND " . count($issues) . " ISSUE(S):\n\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". $issue\n";
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "RECOMMENDED FIXES:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    foreach ($fixes as $i => $fix) {
        echo ($i + 1) . ". $fix\n";
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "QUICK FIXES:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "1. Run migration script:\n";
echo "   php migrate-images-to-public.php\n\n";
echo "2. Fix permissions:\n";
echo "   chmod -R 755 public_html/uploads/\n\n";
echo "3. Clear cache:\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan view:clear\n\n";
echo "4. Test direct URL:\n";
echo "   curl -I " . url('uploads/products/[filename].jpg') . "\n\n";

echo "Diagnostic completed at: " . date('Y-m-d H:i:s') . "\n";
