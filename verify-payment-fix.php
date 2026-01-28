<?php

/**
 * Verification Script - Payment Method Fix
 * 
 * This script verifies that all payment method fixes are properly applied
 */

echo "=== Payment Method Fix Verification ===\n\n";

$checks = [];
$passed = 0;
$failed = 0;

// Check 1: Migration file exists
echo "1. Checking migration file...\n";
$migrationFile = glob(__DIR__ . '/database/migrations/*_add_payment_detail_to_orders_table.php');
if (!empty($migrationFile)) {
    echo "   ✓ Migration file exists\n";
    $checks[] = ['name' => 'Migration file', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ✗ Migration file NOT found\n";
    $checks[] = ['name' => 'Migration file', 'status' => 'FAIL'];
    $failed++;
}

// Check 2: Database column exists
echo "\n2. Checking database column...\n";
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('orders');
    if (in_array('payment_detail', $columns)) {
        echo "   ✓ Column 'payment_detail' exists in orders table\n";
        $checks[] = ['name' => 'Database column', 'status' => 'PASS'];
        $passed++;
    } else {
        echo "   ✗ Column 'payment_detail' NOT found in orders table\n";
        echo "   → Run: php artisan migrate\n";
        $checks[] = ['name' => 'Database column', 'status' => 'FAIL'];
        $failed++;
    }
} catch (\Exception $e) {
    echo "   ✗ Error checking database: {$e->getMessage()}\n";
    $checks[] = ['name' => 'Database column', 'status' => 'ERROR'];
    $failed++;
}

// Check 3: Model has payment_detail in fillable
echo "\n3. Checking Order model...\n";
$modelFile = file_get_contents(__DIR__ . '/app/Models/Order.php');
if (strpos($modelFile, "'payment_detail'") !== false) {
    echo "   ✓ 'payment_detail' found in \$fillable\n";
    $checks[] = ['name' => 'Model fillable', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ✗ 'payment_detail' NOT found in \$fillable\n";
    $checks[] = ['name' => 'Model fillable', 'status' => 'FAIL'];
    $failed++;
}

// Check 4: CheckoutController has mapPaymentToSnap enabled
echo "\n4. Checking CheckoutController...\n";
$controllerFile = file_get_contents(__DIR__ . '/app/Http/Controllers/CheckoutController.php');

// Check if mapPaymentToSnap is called (not commented)
if (preg_match('/\$enabledPayments\s*=\s*\$this->mapPaymentToSnap/', $controllerFile)) {
    echo "   ✓ mapPaymentToSnap() is enabled (not commented)\n";
    $checks[] = ['name' => 'mapPaymentToSnap enabled', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ✗ mapPaymentToSnap() is commented or not found\n";
    $checks[] = ['name' => 'mapPaymentToSnap enabled', 'status' => 'FAIL'];
    $failed++;
}

// Check if payment_detail is saved to order
if (strpos($controllerFile, "'payment_detail' => \$validated['payment_detail']") !== false) {
    echo "   ✓ payment_detail is saved to order\n";
    $checks[] = ['name' => 'Save payment_detail', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ✗ payment_detail is NOT saved to order\n";
    $checks[] = ['name' => 'Save payment_detail', 'status' => 'FAIL'];
    $failed++;
}

// Check if mapPaymentToSnap function exists and is correct
if (preg_match('/private\s+function\s+mapPaymentToSnap/', $controllerFile)) {
    echo "   ✓ mapPaymentToSnap() function exists\n";
    $checks[] = ['name' => 'mapPaymentToSnap function', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ✗ mapPaymentToSnap() function NOT found\n";
    $checks[] = ['name' => 'mapPaymentToSnap function', 'status' => 'FAIL'];
    $failed++;
}

// Check 5: Payment component has getPaymentDetail
echo "\n5. Checking payment component...\n";
$componentFile = file_get_contents(__DIR__ . '/resources/views/components/payment-methods-simple.blade.php');

if (strpos($componentFile, 'getPaymentDetail()') !== false) {
    echo "   ✓ getPaymentDetail() function exists\n";
    $checks[] = ['name' => 'getPaymentDetail function', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ✗ getPaymentDetail() function NOT found\n";
    $checks[] = ['name' => 'getPaymentDetail function', 'status' => 'FAIL'];
    $failed++;
}

// Check if QRIS handling exists
if (strpos($componentFile, "this.selectedPayment === 'qris'") !== false) {
    echo "   ✓ QRIS handling exists in getPaymentDetail()\n";
    $checks[] = ['name' => 'QRIS handling', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ✗ QRIS handling NOT found in getPaymentDetail()\n";
    $checks[] = ['name' => 'QRIS handling', 'status' => 'FAIL'];
    $failed++;
}

// Check 6: Test files exist
echo "\n6. Checking test files...\n";
$testFiles = [
    'test-payment-mapping.php',
    'test-checkout-payment-method.php',
    'PAYMENT_METHOD_FIX.md',
    'SUMMARY_PAYMENT_FIX.md',
    'TEST_PAYMENT_SCENARIOS.md'
];

$testFilesFound = 0;
foreach ($testFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $testFilesFound++;
    }
}

if ($testFilesFound === count($testFiles)) {
    echo "   ✓ All test/documentation files exist ($testFilesFound/" . count($testFiles) . ")\n";
    $checks[] = ['name' => 'Test files', 'status' => 'PASS'];
    $passed++;
} else {
    echo "   ⚠ Some test files missing ($testFilesFound/" . count($testFiles) . ")\n";
    $checks[] = ['name' => 'Test files', 'status' => 'WARN'];
}

// Summary
echo "\n" . str_repeat('=', 50) . "\n";
echo "VERIFICATION SUMMARY\n";
echo str_repeat('=', 50) . "\n\n";

foreach ($checks as $check) {
    $icon = $check['status'] === 'PASS' ? '✓' : ($check['status'] === 'WARN' ? '⚠' : '✗');
    echo sprintf("%-30s %s\n", $check['name'], "$icon {$check['status']}");
}

echo "\n";
echo "Total Checks: " . count($checks) . "\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

if ($failed === 0) {
    echo "\n✓✓✓ ALL CHECKS PASSED! ✓✓✓\n";
    echo "\nPayment method fix is properly applied.\n";
    echo "You can now test in browser.\n\n";
    echo "Next steps:\n";
    echo "1. Open browser and go to checkout page\n";
    echo "2. Select different payment methods\n";
    echo "3. Verify only selected method appears in Midtrans\n";
    echo "4. Check database for payment_detail values\n";
    exit(0);
} else {
    echo "\n✗✗✗ SOME CHECKS FAILED ✗✗✗\n";
    echo "\nPlease fix the failed checks above.\n";
    exit(1);
}
