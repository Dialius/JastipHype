<?php

/**
 * Validate All Payment Methods Against Midtrans Documentation
 * 
 * Berdasarkan dokumentasi Midtrans Snap Advanced Feature:
 * https://docs.midtrans.com/docs/snap-advanced-feature
 */

echo "=== Validating Payment Methods Against Midtrans Docs ===\n\n";

// Valid enabled_payments menurut dokumentasi Midtrans
$validMidtransPayments = [
    'credit_card',
    'gopay',
    'shopeepay',
    'permata_va',
    'bca_va',
    'bni_va',
    'bri_va',
    'echannel',      // Mandiri Bill Payment
    'other_va',
    'indomaret',     // ⚠️ LOWERCASE!
    'alfamart',      // ⚠️ LOWERCASE!
    'akulaku',
    'kredivo',
    'cimb_va',
    'danamon_va',
    'bsi_va',
    'other_qris'     // QRIS via other acquirer
];

echo "Valid Midtrans enabled_payments:\n";
foreach ($validMidtransPayments as $payment) {
    echo "  - $payment\n";
}

echo "\n" . str_repeat('=', 60) . "\n\n";

// Mapping kita saat ini
$ourMapping = [
    'qris' => ['gopay', 'shopeepay'],
    'bank_transfer_bca' => ['bca_va'],
    'bank_transfer_bni' => ['bni_va'],
    'bank_transfer_bri' => ['bri_va'],
    'bank_transfer_mandiri' => ['echannel'],
    'bank_transfer_permata' => ['permata_va'],
    'ewallet_gopay' => ['gopay'],
    'ewallet_shopeepay' => ['shopeepay'],
    'ewallet_dana' => ['gopay'],
    'ewallet_ovo' => ['shopeepay'],
    'convenience_store_indomaret' => ['indomaret'],
    'convenience_store_alfamart' => ['alfamart'],
];

echo "Checking our current mapping...\n\n";

$allValid = true;
$issues = [];

foreach ($ourMapping as $key => $values) {
    echo "Checking: $key\n";
    echo "  Maps to: " . json_encode($values) . "\n";
    
    foreach ($values as $value) {
        if (in_array($value, $validMidtransPayments)) {
            echo "  ✓ '$value' is VALID\n";
        } else {
            echo "  ✗ '$value' is INVALID!\n";
            $allValid = false;
            $issues[] = [
                'mapping' => $key,
                'invalid_value' => $value,
                'suggestion' => findSuggestion($value, $validMidtransPayments)
            ];
        }
    }
    echo "\n";
}

echo str_repeat('=', 60) . "\n\n";

if ($allValid) {
    echo "✓✓✓ ALL MAPPINGS ARE VALID! ✓✓✓\n\n";
    echo "Semua payment method mapping sudah benar sesuai dokumentasi Midtrans.\n";
} else {
    echo "✗✗✗ FOUND ISSUES! ✗✗✗\n\n";
    echo "Issues found:\n";
    foreach ($issues as $issue) {
        echo "  - Mapping: {$issue['mapping']}\n";
        echo "    Invalid value: '{$issue['invalid_value']}'\n";
        echo "    Suggestion: '{$issue['suggestion']}'\n\n";
    }
}

echo "\n" . str_repeat('=', 60) . "\n\n";

// Check case sensitivity issues
echo "Checking for case sensitivity issues...\n\n";

$caseSensitiveChecks = [
    ['our' => 'Indomaret', 'correct' => 'indomaret'],
    ['our' => 'Alfamart', 'correct' => 'alfamart'],
    ['our' => 'INDOMARET', 'correct' => 'indomaret'],
    ['our' => 'ALFAMART', 'correct' => 'alfamart'],
];

foreach ($caseSensitiveChecks as $check) {
    if ($check['our'] === $check['correct']) {
        echo "✓ '{$check['our']}' - Correct case\n";
    } else {
        echo "⚠ '{$check['our']}' should be '{$check['correct']}' (case-sensitive!)\n";
    }
}

echo "\n" . str_repeat('=', 60) . "\n\n";

// Additional notes
echo "IMPORTANT NOTES:\n\n";
echo "1. QRIS:\n";
echo "   - 'qris' is NOT a valid enabled_payments value\n";
echo "   - Use 'gopay' and/or 'shopeepay' for QRIS\n";
echo "   - Midtrans will automatically show QR code\n\n";

echo "2. Convenience Store:\n";
echo "   - Must be LOWERCASE: 'indomaret', 'alfamart'\n";
echo "   - NOT: 'Indomaret', 'Alfamart', 'INDOMARET', 'ALFAMART'\n\n";

echo "3. Mandiri:\n";
echo "   - Use 'echannel' (Mandiri Bill Payment)\n";
echo "   - NOT 'mandiri_va'\n\n";

echo "4. Dana & OVO:\n";
echo "   - No direct integration in Snap\n";
echo "   - Use 'gopay' or 'shopeepay' for QRIS payment\n\n";

echo "5. Other VA:\n";
echo "   - 'other_va' can be used for other banks\n";
echo "   - But specific bank VA is recommended\n\n";

function findSuggestion($invalid, $validList) {
    $lower = strtolower($invalid);
    foreach ($validList as $valid) {
        if (strtolower($valid) === $lower) {
            return $valid;
        }
    }
    
    // Check for similar names
    if (stripos($invalid, 'indomaret') !== false) return 'indomaret';
    if (stripos($invalid, 'alfamart') !== false) return 'alfamart';
    if (stripos($invalid, 'qris') !== false) return 'gopay or shopeepay';
    if (stripos($invalid, 'mandiri') !== false) return 'echannel';
    
    return 'unknown';
}
