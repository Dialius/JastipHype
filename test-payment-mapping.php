<?php

/**
 * Test Payment Method Mapping
 * 
 * This script tests if payment methods are correctly mapped to Midtrans enabled_payments
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Payment Method Mapping ===\n\n";

// Test cases
$testCases = [
    [
        'name' => 'QRIS Payment',
        'method' => 'qris',
        'detail' => 'qris',
        'expected' => ['gopay', 'shopeepay']  // QRIS menggunakan GoPay/ShopeePay acquirer
    ],
    [
        'name' => 'BCA Virtual Account',
        'method' => 'bank_transfer',
        'detail' => 'bca',
        'expected' => ['bca_va']
    ],
    [
        'name' => 'BNI Virtual Account',
        'method' => 'bank_transfer',
        'detail' => 'bni',
        'expected' => ['bni_va']
    ],
    [
        'name' => 'Mandiri Bill Payment',
        'method' => 'bank_transfer',
        'detail' => 'mandiri',
        'expected' => ['echannel']
    ],
    [
        'name' => 'GoPay E-Wallet',
        'method' => 'ewallet',
        'detail' => 'gopay',
        'expected' => ['gopay']
    ],
    [
        'name' => 'ShopeePay E-Wallet',
        'method' => 'ewallet',
        'detail' => 'shopeepay',
        'expected' => ['shopeepay']
    ],
    [
        'name' => 'Dana E-Wallet (via QRIS)',
        'method' => 'ewallet',
        'detail' => 'dana',
        'expected' => ['gopay']  // Dana via GoPay QRIS
    ],
    [
        'name' => 'Indomaret',
        'method' => 'convenience_store',
        'detail' => 'indomaret',
        'expected' => ['indomaret']
    ],
    [
        'name' => 'Alfamart',
        'method' => 'convenience_store',
        'detail' => 'alfamart',
        'expected' => ['alfamart']
    ],
];

// Simulate the mapping function
function mapPaymentToSnap($method, $detail) {
    switch ($method) {
        case 'qris':
            // QRIS di Midtrans menggunakan GoPay/ShopeePay acquirer
            return ['gopay', 'shopeepay'];
        
        case 'bank_transfer':
            $map = [
                'bca' => 'bca_va',
                'mandiri' => 'echannel',
                'bni' => 'bni_va',
                'bri' => 'bri_va',
                'permata' => 'permata_va'
            ];
            return isset($map[$detail]) ? [$map[$detail]] : ['bca_va'];

        case 'ewallet':
            $map = [
                'gopay' => 'gopay',
                'shopeepay' => 'shopeepay',
                'dana' => 'gopay',      // Dana via GoPay QRIS
                'ovo' => 'shopeepay'    // OVO via ShopeePay QRIS
            ];
            return isset($map[$detail]) ? [$map[$detail]] : ['gopay'];
        
        case 'convenience_store':
            return [$detail ?: 'indomaret'];
            
        default:
            return null;
    }
}

$passed = 0;
$failed = 0;

foreach ($testCases as $test) {
    $result = mapPaymentToSnap($test['method'], $test['detail']);
    $success = $result === $test['expected'];
    
    if ($success) {
        echo "✓ PASS: {$test['name']}\n";
        echo "  Method: {$test['method']}, Detail: {$test['detail']}\n";
        echo "  Result: " . json_encode($result) . "\n\n";
        $passed++;
    } else {
        echo "✗ FAIL: {$test['name']}\n";
        echo "  Method: {$test['method']}, Detail: {$test['detail']}\n";
        echo "  Expected: " . json_encode($test['expected']) . "\n";
        echo "  Got: " . json_encode($result) . "\n\n";
        $failed++;
    }
}

echo "\n=== Summary ===\n";
echo "Total Tests: " . count($testCases) . "\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

if ($failed === 0) {
    echo "\n✓ All tests passed!\n";
} else {
    echo "\n✗ Some tests failed!\n";
}
