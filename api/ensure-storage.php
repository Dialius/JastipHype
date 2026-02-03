<?php

/**
 * Ensure storage directories exist at runtime
 * This runs when the serverless function is invoked
 */

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

// Return success
http_response_code(200);
echo json_encode(['status' => 'ok', 'message' => 'Storage directories ensured']);
