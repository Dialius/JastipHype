<?php

require __DIR__ . '/vendor/autoload.php';

use Midtrans\Config;
use Midtrans\CoreApi;

// Set configuration
Config::$serverKey = 'Mid-server-ezqCCqbhe-zfF83kGA1ETmVy';
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

echo "Testing Midtrans Core API Integration\n";
echo "=====================================\n\n";

// Test 1: QRIS
echo "1. Testing QRIS Payment:\n";
try {
    $params = [
        'payment_type' => 'qris',
        'transaction_details' => [
            'order_id' => 'TEST-QRIS-' . time(),
            'gross_amount' => 10000,
        ],
        'qris' => [
            'acquirer' => 'gopay'
        ]
    ];
    
    $response = CoreApi::charge($params);
    echo "   ✓ QRIS Success!\n";
    echo "   Transaction ID: " . $response->transaction_id . "\n";
    echo "   Status: " . $response->transaction_status . "\n";
    if (isset($response->qr_string)) {
        echo "   QR String: " . substr($response->qr_string, 0, 50) . "...\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "   ✗ QRIS Failed: " . $e->getMessage() . "\n\n";
}

// Test 2: Bank Transfer (BCA)
echo "2. Testing Bank Transfer (BCA):\n";
try {
    $params = [
        'payment_type' => 'bank_transfer',
        'transaction_details' => [
            'order_id' => 'TEST-BCA-' . time(),
            'gross_amount' => 10000,
        ],
        'bank_transfer' => [
            'bank' => 'bca'
        ]
    ];
    
    $response = CoreApi::charge($params);
    echo "   ✓ BCA VA Success!\n";
    echo "   Transaction ID: " . $response->transaction_id . "\n";
    echo "   Status: " . $response->transaction_status . "\n";
    if (isset($response->va_numbers)) {
        echo "   VA Number: " . $response->va_numbers[0]->va_number . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "   ✗ BCA VA Failed: " . $e->getMessage() . "\n\n";
}

// Test 3: GoPay
echo "3. Testing GoPay:\n";
try {
    $params = [
        'payment_type' => 'gopay',
        'transaction_details' => [
            'order_id' => 'TEST-GOPAY-' . time(),
            'gross_amount' => 10000,
        ],
        'gopay' => [
            'enable_callback' => true,
            'callback_url' => 'https://example.com/callback'
        ]
    ];
    
    $response = CoreApi::charge($params);
    echo "   ✓ GoPay Success!\n";
    echo "   Transaction ID: " . $response->transaction_id . "\n";
    echo "   Status: " . $response->transaction_status . "\n";
    if (isset($response->actions)) {
        echo "   Actions available: " . count($response->actions) . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "   ✗ GoPay Failed: " . $e->getMessage() . "\n\n";
}

// Test 4: Mandiri Bill Payment
echo "4. Testing Mandiri Bill Payment:\n";
try {
    $params = [
        'payment_type' => 'echannel',
        'transaction_details' => [
            'order_id' => 'TEST-MANDIRI-' . time(),
            'gross_amount' => 10000,
        ],
        'echannel' => [
            'bill_info1' => 'Payment For:',
            'bill_info2' => 'Test Order'
        ]
    ];
    
    $response = CoreApi::charge($params);
    echo "   ✓ Mandiri Bill Success!\n";
    echo "   Transaction ID: " . $response->transaction_id . "\n";
    echo "   Status: " . $response->transaction_status . "\n";
    if (isset($response->bill_key)) {
        echo "   Bill Key: " . $response->bill_key . "\n";
        echo "   Biller Code: " . $response->biller_code . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "   ✗ Mandiri Bill Failed: " . $e->getMessage() . "\n\n";
}

// Test 5: Convenience Store (Indomaret)
echo "5. Testing Convenience Store (Indomaret):\n";
try {
    $params = [
        'payment_type' => 'cstore',
        'transaction_details' => [
            'order_id' => 'TEST-INDOMARET-' . time(),
            'gross_amount' => 10000,
        ],
        'cstore' => [
            'store' => 'indomaret',
            'message' => 'Test Payment'
        ]
    ];
    
    $response = CoreApi::charge($params);
    echo "   ✓ Indomaret Success!\n";
    echo "   Transaction ID: " . $response->transaction_id . "\n";
    echo "   Status: " . $response->transaction_status . "\n";
    if (isset($response->payment_code)) {
        echo "   Payment Code: " . $response->payment_code . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "   ✗ Indomaret Failed: " . $e->getMessage() . "\n\n";
}

echo "=====================================\n";
echo "Test completed!\n";
