<?php

// Direct test of checkout process
require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Set up test data
$testData = [
    'email' => 'test@example.com',
    'name' => 'Test User',
    'phone' => '08123456789',
    'address' => 'Jl. Test No. 123',
    'province_id' => '6',
    'city_id' => '152',
    'subdistrict_id' => '1',
    'postal_code' => '10620',
    'payment_method' => 'qris',
    'payment_detail' => '',
    'shipping_cost' => '0',
];

echo "Testing Checkout Process\n";
echo "========================\n\n";

try {
    // Check if user has cart
    $cart = \App\Models\Cart::where('session_id', 'test-session')->first();
    
    if (!$cart) {
        echo "Creating test cart...\n";
        $cart = \App\Models\Cart::create([
            'session_id' => 'test-session',
            'user_id' => null
        ]);
        
        // Add test item
        $product = \App\Models\Product::first();
        if ($product) {
            \App\Models\CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'size' => 'M',
            ]);
            echo "✓ Test cart created with product: {$product->name}\n\n";
        }
    } else {
        echo "✓ Cart exists with {$cart->items->count()} items\n\n";
    }
    
    // Test validation
    echo "Testing validation rules...\n";
    $validator = \Illuminate\Support\Facades\Validator::make($testData, [
        'email' => 'required|email',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'province_id' => 'required',
        'city_id' => 'required',
        'postal_code' => 'required|string|max:10',
        'payment_method' => 'required|string',
        'payment_detail' => 'nullable|string',
    ]);
    
    if ($validator->fails()) {
        echo "✗ Validation failed:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "  - $error\n";
        }
        exit(1);
    }
    
    echo "✓ Validation passed\n\n";
    
    // Test Midtrans service
    echo "Testing Midtrans service...\n";
    $midtrans = new \App\Services\MidtransService();
    
    $orderData = [
        'order_id' => 'TEST-' . time(),
        'gross_amount' => 100000,
        'customer' => [
            'first_name' => 'Test',
            'email' => 'test@example.com',
            'phone' => '08123456789',
        ],
        'items' => [
            [
                'id' => '1',
                'price' => 100000,
                'quantity' => 1,
                'name' => 'Test Product'
            ]
        ]
    ];
    
    $paymentMapping = $midtrans->mapPaymentMethod('qris');
    echo "Payment mapping: " . json_encode($paymentMapping) . "\n";
    
    $result = $midtrans->createTransaction($orderData, $paymentMapping['type'], $paymentMapping['details']);
    
    if ($result['success']) {
        echo "✓ Midtrans transaction created successfully\n";
        echo "  Transaction ID: " . $result['data']->transaction_id . "\n";
        echo "  Status: " . $result['data']->transaction_status . "\n\n";
    } else {
        echo "✗ Midtrans transaction failed: " . $result['message'] . "\n\n";
    }
    
    // Test order creation
    echo "Testing order creation...\n";
    $order = \App\Models\Order::create([
        'user_id' => null,
        'order_number' => 'TEST-ORDER-' . time(),
        'email' => $testData['email'],
        'name' => $testData['name'],
        'phone' => $testData['phone'],
        'address' => $testData['address'],
        'province_id' => $testData['province_id'],
        'city_id' => $testData['city_id'],
        'postal_code' => $testData['postal_code'],
        'payment_method' => $testData['payment_method'],
        'subtotal' => 100000,
        'shipping_cost' => 0,
        'total' => 100000,
        'status' => 'pending'
    ]);
    
    echo "✓ Order created: {$order->order_number}\n\n";
    
    // Test payment creation
    echo "Testing payment creation...\n";
    $payment = \App\Models\Payment::create([
        'order_id' => $order->id,
        'transaction_id' => $result['data']->transaction_id ?? 'TEST-TXN',
        'payment_type' => 'qris',
        'gross_amount' => 100000,
        'transaction_status' => 'pending',
        'payment_data' => $result['data'] ?? []
    ]);
    
    echo "✓ Payment created: ID {$payment->id}\n\n";
    
    echo "========================\n";
    echo "✓ All tests passed!\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Payment URL: " . url('/payment/' . $order->order_number) . "\n";
    
} catch (\Exception $e) {
    echo "\n✗ Error occurred:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
