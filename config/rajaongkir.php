<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for RajaOngkir shipping cost calculator API.
    | Get your API key from: https://rajaongkir.com/
    |
    | Types: starter (free), basic, pro
    |
    */

    'api_key' => env('RAJAONGKIR_API_KEY'),
    
    'type' => env('RAJAONGKIR_TYPE', 'starter'),
    
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter'),
    
    /*
    | Origin city (your warehouse/store location)
    | Find city ID from RajaOngkir API
    */
    'origin' => env('RAJAONGKIR_ORIGIN', '151'), // Jakarta Pusat default
    
    /*
    | Default weight if product weight not set (in grams)
    */
    'default_weight' => 1000,
    
    /*
    | Available couriers
    | starter: jne, pos, tiki
    | basic/pro: jne, pos, tiki, rpx, pcp, emspost, sap
    */
    'couriers' => ['jne', 'tiki', 'pos'],
];
