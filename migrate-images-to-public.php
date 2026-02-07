<?php

/**
 * Script to migrate images from storage/app/public to public/uploads
 * This is needed because symlink doesn't work on Hostinger shared hosting
 * 
 * Run this script ONCE after deployment:
 * php migrate-images-to-public.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

echo "===========================================\n";
echo "MIGRATE IMAGES TO PUBLIC FOLDER\n";
echo "===========================================\n\n";

$sourceDir = storage_path('app/public');
$targetDir = public_path('uploads');

echo "Source: $sourceDir\n";
echo "Target: $targetDir\n\n";

// Check if source directory exists
if (!File::exists($sourceDir)) {
    echo "❌ Source directory does not exist!\n";
    exit(1);
}

// Create target directory if it doesn't exist
if (!File::exists($targetDir)) {
    echo "📁 Creating target directory...\n";
    File::makeDirectory($targetDir, 0755, true);
}

// Get all files recursively from source
$files = File::allFiles($sourceDir);
$totalFiles = count($files);
$copiedFiles = 0;
$skippedFiles = 0;
$errors = 0;

echo "Found $totalFiles files to migrate\n\n";

foreach ($files as $file) {
    $relativePath = str_replace($sourceDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
    $relativePath = str_replace('\\', '/', $relativePath); // Normalize path separators
    
    $targetPath = $targetDir . DIRECTORY_SEPARATOR . $relativePath;
    $targetPathDir = dirname($targetPath);
    
    echo "Processing: $relativePath\n";
    
    try {
        // Create subdirectory if needed
        if (!File::exists($targetPathDir)) {
            File::makeDirectory($targetPathDir, 0755, true);
        }
        
        // Check if file already exists
        if (File::exists($targetPath)) {
            echo "  ⏭️  Already exists, skipping\n";
            $skippedFiles++;
            continue;
        }
        
        // Copy file
        if (File::copy($file->getPathname(), $targetPath)) {
            echo "  ✅ Copied successfully\n";
            $copiedFiles++;
        } else {
            echo "  ❌ Failed to copy\n";
            $errors++;
        }
        
    } catch (\Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n===========================================\n";
echo "MIGRATION COMPLETE\n";
echo "===========================================\n";
echo "Total files: $totalFiles\n";
echo "Copied: $copiedFiles\n";
echo "Skipped: $skippedFiles\n";
echo "Errors: $errors\n\n";

if ($errors > 0) {
    echo "⚠️  Some files failed to migrate. Please check the errors above.\n";
    exit(1);
} else {
    echo "✅ All files migrated successfully!\n";
    echo "\n📝 NEXT STEPS:\n";
    echo "1. Test your website to ensure images are loading\n";
    echo "2. If everything works, you can safely delete storage/app/public\n";
    echo "3. Commit and push changes to GitHub\n";
    echo "4. Deploy to production\n";
}
