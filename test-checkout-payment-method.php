<?php

/**
 * Test Checkout with Different Payment Methods
 * 
 * This script simulates checkout with different payment methods
 * to verify that the correct payment method is sent to Midtrans
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Order;

echo "=== Testing Checkout Payment Method Selection ===\n\n";

// Test scenarios
$testScenarios = [
    [
        'name' => 'QRIS Payment',
        'payment_method' => 'qris',
        'payment_detail' => 'qris',
        'expected_enabled_payments' => ['qris']
    ],
    [
        'name' => 'BNI Virtual Account',
        'payment_method' => 'bank_transfer',
        'payment_detail' => 'bni',
        'expected_enabled_payments' => ['bni_va']
    ],
    [
        'name' => 'BCA Virtual Account',
        'payment_method' => 'bank_transfer',
        'payment_detail' => 'bca',
        'expected_enabled_payments' => ['bca_va']
    ],
    [
        'name' => 'GoPay E-Wallet',
        'payment_method' => 'ewallet',
        'payment_detail' => 'gopay',
        'expected_enabled_payments' => ['gopay']
    ],
];

echo "Creating test orders...\n\n";

foreach ($testScenarios as $scenario) {
    echo "Testing: {$scenario['name']}\n";
    echo str_repeat('-', 50) . "\n";
    
    try {
        // Create test order
        $order = Order::create([
            'user_id' => null,
            'order_number' => 'TEST-' . strtoupper(uniqid()),
            'email' => 'test@example.com',
            'name' => 'Test User',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'province_id' => '6',
            'city_id' => '152',
            'postal_code' => '10110',
            'payment_method' => $scenario['payment_method'],
            'payment_detail' => $scenario['payment_detail'],
            'subtotal' => 100000,
            'shipping_cost' => 10000,
            'total' => 110000,
            'status' => 'pending'
        ]);
        
        echo "✓ Order created: {$order->order_number}\n";
        echo "  Payment Method: {$order->payment_method}\n";
        echo "  Payment Detail: {$order->payment_detail}\n";
        
        // Simulate mapping
        $enabledPayments = mapPaymentToSnap($order->payment_method, $order->payment_detail);
        echo "  Enabled Payments: " . json_encode($enabledPayments) . "\n";
        
        // Verify
        if ($enabledPayments === $scenario['expected_enabled_payments']) {
            echo "  ✓ Mapping CORRECT\n";
        } else {
            echo "  ✗ Mapping INCORRECT\n";
            echo "    Expected: " . json_encode($scenario['expected_enabled_payments']) . "\n";
            echo "    Got: " . json_encode($enabledPayments) . "\n";
        }
        
        // Clean up test order
        $order->delete();
        echo "  ✓ Test order cleaned up\n";
        
    } catch (\Exception $e) {
        echo "✗ Error: {$e->getMessage()}\n";
    }
    
    echo "\n";
}

// Helper function (same as in CheckoutController)
function mapPaymentToSnap($method, $detail) {
    switch ($method) {
        case 'qris':
            return ['qris'];
        
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
                'dana' => 'qris',
                'ovo' => 'qris'
            ];
            return isset($map[$detail]) ? [$map[$detail]] : ['gopay'];
        
        case 'convenience_store':
            return [$detail ?: 'indomaret'];
            
        default:
            return null;
    }
}

echo "=== Test Complete ===\n";
echo "\nNext Steps:\n";
echo "1. Test dengan browser: Buka halaman checkout\n";
echo "2. Pilih payment method (misal: BNI)\n";
echo "3. Submit dan cek halaman payment Midtrans\n";
echo "4. Verifikasi hanya BNI VA yang muncul\n";
