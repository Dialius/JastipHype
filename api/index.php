<?php

// Ensure storage directories exist in serverless environment
if (!empty(getenv('VERCEL_ENV'))) {
    $directories = [
        '/tmp/storage/banners',
        '/tmp/storage/brands',
        '/tmp/storage/categories',
        '/tmp/storage/products',
        '/tmp/storage/reviews',
    ];

    foreach ($directories as $directory) {
        if (!file_exists($directory)) {
            @mkdir($directory, 0755, true);
        }
    }
}

// Forward to the standard Laravel entry point
require __DIR__ . '/../public/index.php';
