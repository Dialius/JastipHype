<?php
/**
 * Production Storage Fix & Debug Script
 * Upload this file to your public_html folder and run it via browser:
 * https://jastiphype.shop/production-fix.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Locate bootstrap file
$bootstrapPath = __DIR__.'/../bootstrap/app.php'; // Adjust if in public_html
if (!file_exists($bootstrapPath)) {
    $bootstrapPath = __DIR__.'/bootstrap/app.php'; // Try root
}

if (!file_exists($bootstrapPath)) {
    die("❌ Error: Could not find bootstrap/app.php. Please make sure this file is in your public folder or root folder.");
}

require $bootstrapPath;

echo "<h1>🛠️ JastipHype Production Storage Fix</h1>";
echo "<pre>";

// 1. Check APP_URL
echo "<h3>1. Environment Check</h3>";
echo "APP_URL (from config): " . config('app.url') . "\n";
echo "Current Host: " . $_SERVER['HTTP_HOST'] . "\n";

if (strpos(config('app.url'), $_SERVER['HTTP_HOST']) === false) {
    echo "⚠️  WARNING: APP_URL in .env might be different from current host.\n";
} else {
    echo "✅ APP_URL looks correct.\n";
}

// 2. Fix Storage Link
echo "\n<h3>2. Storage Link Fix</h3>";
$publicStorage = __DIR__ . '/storage';
$targetStorage = realpath(__DIR__ . '/../storage/app/public');

// Adjust if script is in root, not public
if (!$targetStorage) {
    $targetStorage = realpath(__DIR__ . '/storage/app/public');
}

echo "Target Storage Path: " . ($targetStorage ?: 'NOT FOUND') . "\n";
echo "Public Link Path: " . $publicStorage . "\n";

if (!$targetStorage) {
    echo "❌ ERROR: Could not find storage/app/public directory.\n";
} else {
    if (file_exists($publicStorage)) {
        if (is_link($publicStorage)) {
            echo "ℹ️  Link already exists.\n";
            echo "   Linked to: " . readlink($publicStorage) . "\n";
            if (readlink($publicStorage) !== $targetStorage) {
                 echo "⚠️  Link points to wrong location! Attempting to fix...\n";
                 unlink($publicStorage);
                 if (symlink($targetStorage, $publicStorage)) {
                     echo "✅  Link recreated successfully!\n";
                 } else {
                     echo "❌  Failed to recreate link. Check permissions.\n";
                 }
            } else {
                echo "✅  Link is correct.\n";
            }
        } else {
            echo "⚠️  'storage' exists but is a DIRECTORY, not a link. This is bad.\n";
            echo "   Attempting to rename it to storage_backup...\n";
            rename($publicStorage, $publicStorage . '_backup_' . time());
            if (symlink($targetStorage, $publicStorage)) {
                 echo "✅  Link created successfully!\n";
             } else {
                 echo "❌  Failed to create link. Check permissions.\n";
             }
        }
    } else {
        echo "ℹ️  Link does not exist. Creating...\n";
        if (symlink($targetStorage, $publicStorage)) {
             echo "✅  Link created successfully!\n";
         } else {
             echo "❌  Failed to create link. Check permissions.\n";
         }
    }
}

// 3. Check Files
echo "\n<h3>3. File Existence Check</h3>";
if ($targetStorage) {
    $categories = glob($targetStorage . '/categories/*');
    $banners = glob($targetStorage . '/banners/*');
    
    echo "Found " . count($categories) . " files in storage/categories\n";
    echo "Found " . count($banners) . " files in storage/banners\n";
    
    if (count($categories) > 0) {
        $sample = basename($categories[0]);
        echo "Sample Category Image: $sample\n";
        echo "Public URL: " . config('app.url') . "/storage/categories/$sample\n";
        echo "<a href='" . config('app.url') . "/storage/categories/$sample' target='_blank'>Click to Open Sample Image</a>\n";
    }
    
    if (count($categories) === 0 && count($banners) === 0) {
        echo "\n⚠️  <strong>IMPORTANT: No images found!</strong>\n";
        echo "You likely need to upload your 'storage/app/public' folder from your local computer\n";
        echo "to 'storage/app/public' on the server via FTP/File Manager.\n";
    }
}

echo "</pre>";
