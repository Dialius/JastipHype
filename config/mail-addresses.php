<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Addresses Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi alamat email untuk berbagai keperluan sistem
    |
    */

    'admin' => env('MAIL_ADMIN', 'admin@jastiphype.shop'),
    'order' => env('MAIL_ORDER', 'order@jastiphype.shop'),
    'support' => env('MAIL_SUPPORT', 'support@jastiphype.shop'),
    'noreply' => env('MAIL_NOREPLY', 'noreply@jastiphype.shop'),
    'info' => env('MAIL_FROM_ADDRESS', 'info@jastiphype.shop'),
];
