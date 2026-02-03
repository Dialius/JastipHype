#!/usr/bin/env php
<?php

/**
 * Ensure storage directories exist
 * This script is run during deployment to ensure all required directories exist
 */

$directories = [
    'storage/app/public/banners',
    'storage/app/public/brands',
    'storage/app/public/categories',
    'storage/app/public/products',
    'storage/app/public/reviews',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
];

$baseDir = dirname(__DIR__);

echo "Ensuring storage directories exist...\n";

foreach ($directories as $directory) {
    $fullPath = $baseDir . '/' . $directory;
    
    if (!file_exists($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "✓ Created: {$directory}\n";
        } else {
            echo "✗ Failed to create: {$directory}\n";
        }
    } else {
        echo "✓ Exists: {$directory}\n";
    }
}

// Create .gitkeep files
foreach ($directories as $directory) {
    $fullPath = $baseDir . '/' . $directory;
    $gitkeepPath = $fullPath . '/.gitkeep';
    
    if (!file_exists($gitkeepPath)) {
        if (touch($gitkeepPath)) {
            echo "✓ Created .gitkeep in: {$directory}\n";
        }
    }
}

// Ensure storage link exists (for local development)
$publicStorage = $baseDir . '/public/storage';
$storagePublic = $baseDir . '/storage/app/public';

if (!file_exists($publicStorage) && file_exists($storagePublic)) {
    if (PHP_OS_FAMILY === 'Windows') {
        // Windows: use junction
        exec("mklink /J \"{$publicStorage}\" \"{$storagePublic}\"", $output, $returnCode);
    } else {
        // Unix: use symlink
        if (symlink($storagePublic, $publicStorage)) {
            echo "✓ Created storage symlink\n";
        } else {
            echo "✗ Failed to create storage symlink\n";
        }
    }
}

echo "\nStorage setup complete!\n";
