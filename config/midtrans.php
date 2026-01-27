<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans payment gateway integration
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'G689132907'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'Mid-client-DTSB6VSWG4ReInb0'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'Mid-server-ezqCCqbhe-zfF83kGA1ETmVy'),
    
    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Set to true for production, false for sandbox/testing
    |
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    /*
    |--------------------------------------------------------------------------
    | Sanitization
    |--------------------------------------------------------------------------
    |
    | Enable/disable request sanitization
    |
    */
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    
    /*
    |--------------------------------------------------------------------------
    | 3DS
    |--------------------------------------------------------------------------
    |
    | Enable/disable 3D Secure for credit card transactions
    |
    */
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
