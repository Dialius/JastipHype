# Simplified Payment Flow - Midtrans Snap Only

## Overview
Payment method selection has been moved entirely to Midtrans Snap UI. Users no longer select payment methods on the website - they choose directly in Midtrans after clicking "Place Order".

## Changes Made

### 1. Checkout Page (`resources/views/checkout/index.blade.php`)
- **REMOVED**: Payment Method selection section (Step 3)
- **ADDED**: Hidden inputs for payment method
  ```html
  <input type="hidden" name="payment_method" value="snap">
  <input type="hidden" name="payment_detail" value="all">
  ```
- **REMOVED**: Payment method validation in JavaScript

### 2. Checkout Controller (`app/Http/Controllers/CheckoutController.php`)
- **CHANGED**: `payment_method` validation from `required` to `nullable`
- **CHANGED**: Order creation sets `payment_method` to `'snap'` by default
- **REMOVED**: `enabled_payments` parameter from Midtrans transaction (shows all methods)
- **REMOVED**: `mapPaymentToSnap()` function (no longer needed)

### 3. Payment Show Page (`resources/views/payment/show.blade.php`)
- **SIMPLIFIED**: Directly embeds Midtrans Snap UI
- **REMOVED**: Manual payment instruction partials (QRIS, Bank Transfer, etc.)
- **IMPROVED**: Better UI for Snap container with clear instructions

### 4. Payment Controller (`app/Http/Controllers/PaymentController.php`)
- **UPDATED**: `show()` method checks for snap_token
- **SIMPLIFIED**: No need to generate manual instructions when snap_token exists

## User Flow

### Before (Complex)
1. User fills checkout form
2. User selects payment method (QRIS/Bank/E-wallet/etc.)
3. User selects specific provider (BCA/Mandiri/GoPay/etc.)
4. Click "Place Order"
5. Redirected to payment page with specific instructions

### After (Simplified)
1. User fills checkout form
2. Click "Place Order"
3. **Directly see Midtrans Snap UI with ALL payment options**
4. User selects payment method in Midtrans
5. Complete payment

## Benefits

✅ **Simpler Implementation**: No need to maintain payment method UI on website
✅ **Better UX**: All payment options in one place (Midtrans UI)
✅ **Easier Maintenance**: Midtrans handles payment method updates
✅ **More Payment Options**: Automatically get new payment methods from Midtrans
✅ **Consistent Experience**: Same payment UI across all devices
✅ **Less Code**: Removed ~200 lines of payment selection code

## Technical Details

### Midtrans Snap Configuration
```php
$orderData = [
    'order_id' => $order->order_number,
    'gross_amount' => $total,
    'customer' => [...],
    'items' => $itemDetails
    // No 'enabled_payments' - shows all available methods
];
```

### Payment Record
```php
Order::create([
    'payment_method' => 'snap', // Generic, will be updated after payment
    'payment_detail' => null,   // Will be updated after payment
    // ... other fields
]);
```

### Snap Embed
```javascript
window.snap.embed('SNAP_TOKEN', {
    embedId: 'snap-container',
    onSuccess: function(result) { window.location.reload(); },
    onPending: function(result) { window.location.reload(); },
    onError: function(result) { alert('Payment failed'); },
    onClose: function() { /* Optional handling */ }
});
```

## Available Payment Methods in Midtrans Snap

When no `enabled_payments` is specified, Midtrans shows:

### E-Wallets
- GoPay
- ShopeePay
- QRIS (Universal QR)

### Virtual Account
- BCA VA
- BNI VA
- BRI VA
- Mandiri Bill
- Permata VA

### Convenience Store
- Indomaret
- Alfamart

### Credit Card
- Visa
- Mastercard
- JCB
- Amex

## Testing

1. Add items to cart
2. Go to checkout
3. Fill in shipping information
4. Click "Place Order"
5. You should see Midtrans Snap UI with all payment options
6. Select any payment method and complete payment

## Rollback (If Needed)

If you need to restore the old payment selection UI:
1. Restore `resources/views/checkout/index.blade.php` (add back Step 3)
2. Restore `CheckoutController.php` validation and `mapPaymentToSnap()` function
3. Restore `payment/show.blade.php` with instruction partials
4. Add back `enabled_payments` parameter in Midtrans transaction

## Notes

- Payment method will be updated in database after successful payment via webhook
- Order status tracking remains the same
- Webhook handler will update `payment_method` and `payment_detail` based on actual payment
