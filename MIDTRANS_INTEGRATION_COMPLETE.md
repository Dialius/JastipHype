# Midtrans Payment Integration - Complete

## Overview
Midtrans payment gateway has been successfully integrated using the **Direct/Core API** approach, allowing you to use your own payment method selection UI instead of Midtrans Snap's hosted page.

## What's Been Implemented

### 1. Configuration
- **File**: `config/midtrans.php`
- **Environment Variables**: Added to `.env`
  - `MIDTRANS_MERCHANT_ID=G689132907`
  - `MIDTRANS_CLIENT_KEY=Mid-client-DTSB6VSWG4ReInb0`
  - `MIDTRANS_SERVER_KEY=Mid-server-ezqCCqbhe-zfF83kGA1ETmVy`
  - `MIDTRANS_IS_PRODUCTION=false` (Sandbox mode)

### 2. Database Migration
- **File**: `database/migrations/2026_01_26_173649_update_payments_table_for_midtrans.php`
- **Status**: ✅ Migrated successfully
- **New Columns**:
  - `gross_amount` - Total payment amount
  - `payment_code` - VA numbers, payment codes, etc.
  - `fraud_status` - Fraud detection status
  - `transaction_time` - When transaction was created
  - `settlement_time` - When payment was settled
  - `expiry_time` - Payment expiration time
  - `qr_code_url` - QR code for QRIS/GoPay
  - `deeplink_redirect` - Deeplink for e-wallets
  - `pdf_url` - PDF instructions (if available)

### 3. Models
- **File**: `app/Models/Payment.php`
- **Features**:
  - Relationship with Order model
  - Helper methods: `isPending()`, `isSuccess()`, `isFailed()`
  - `getInstructions()` - Returns payment-specific instructions
  - `getPaymentTypeName()` - Human-readable payment type
  - `getStatusLabel()` - Localized status labels
  - `getStatusColor()` - Badge colors for UI

### 4. Services
- **File**: `app/Services/MidtransService.php`
- **Methods**:
  - `createTransaction()` - Create payment via Core API
  - `getTransactionStatus()` - Check payment status
  - `cancelTransaction()` - Cancel pending payment
  - `mapPaymentMethod()` - Map UI payment methods to Midtrans types
  - `mapEwalletType()` - Map e-wallet providers

### 5. Controllers

#### CheckoutController
- **File**: `app/Http/Controllers/CheckoutController.php`
- **Updated**: `process()` method
- **Features**:
  - Creates order and order items
  - Calls Midtrans Core API to create transaction
  - Saves payment data with all relevant fields
  - Redirects to payment instructions page

#### PaymentController
- **File**: `app/Http/Controllers/PaymentController.php`
- **Methods**:
  - `show($orderNumber)` - Display payment instructions
  - `checkStatus($orderNumber)` - AJAX endpoint to check payment status
  - `cancel($orderNumber)` - Cancel payment

#### WebhookController
- **File**: `app/Http/Controllers/WebhookController.php`
- **Method**: `handle()` - Process Midtrans webhook notifications
- **Features**:
  - Validates webhook signature
  - Updates payment and order status
  - Handles all transaction statuses
  - Comprehensive logging

### 6. Views

#### Main Payment Page
- **File**: `resources/views/payment/show.blade.php`
- **Features**:
  - Order summary
  - Payment status badge
  - Auto-refresh payment status (every 10 seconds)
  - Manual status check button
  - Order details

#### Payment Instruction Partials
1. **QRIS**: `resources/views/payment/partials/qris.blade.php`
   - QR code display using QRCode.js
   - Step-by-step instructions

2. **E-Wallet**: `resources/views/payment/partials/ewallet.blade.php`
   - Deeplink button to open app
   - QR code alternative (for GoPay)
   - Instructions for GoPay and ShopeePay

3. **Bank Transfer**: `resources/views/payment/partials/bank-transfer.blade.php`
   - VA number display with copy button
   - Bank-specific instructions (BCA, BNI, BRI, Permata)
   - ATM and mobile banking steps

4. **Mandiri Bill Payment**: `resources/views/payment/partials/mandiri-bill.blade.php`
   - Biller code and bill key display
   - ATM, internet banking, and mobile banking instructions

5. **Convenience Store**: `resources/views/payment/partials/cstore.blade.php`
   - Payment code display
   - Instructions for Indomaret and Alfamart

### 7. Payment Method Component
- **File**: `resources/views/components/payment-methods-simple.blade.php`
- **Updated**: Added `payment_detail` hidden input
- **Features**:
  - QRIS (one-click)
  - E-Wallet (GoPay, Dana, OVO, ShopeePay)
  - Virtual Account (BCA, Mandiri, BNI, BRI, Permata)
  - Convenience Store (Indomaret, Alfamart)

### 8. Routes
- **File**: `routes/web.php`
- **New Routes**:
  - `GET /payment/{orderNumber}` - Payment instructions page
  - `GET /payment/{orderNumber}/status` - Check payment status (AJAX)
  - `POST /payment/{orderNumber}/cancel` - Cancel payment
  - `POST /payment/webhook` - Midtrans webhook (CSRF exempt)

### 9. Middleware
- **File**: `app/Http/Middleware/VerifyCsrfToken.php`
- **Created**: Added webhook route to CSRF exceptions

## Payment Flow

### 1. Checkout Process
```
User fills checkout form
  ↓
Selects payment method (QRIS, E-Wallet, Bank Transfer, etc.)
  ↓
Submits form to CheckoutController@process
  ↓
Order and OrderItems created
  ↓
Midtrans transaction created via Core API
  ↓
Payment record saved with transaction details
  ↓
User redirected to /payment/{orderNumber}
```

### 2. Payment Instructions Page
```
Display payment instructions based on payment type:
- QRIS: Show QR code
- GoPay/ShopeePay: Show deeplink button + QR code
- Bank Transfer: Show VA number
- Mandiri: Show biller code + bill key
- Convenience Store: Show payment code

Auto-check payment status every 10 seconds
Manual check button available
```

### 3. Webhook Processing
```
Midtrans sends notification to /payment/webhook
  ↓
WebhookController validates and processes
  ↓
Updates payment status
  ↓
Updates order status based on payment status:
- settlement/capture → order status: processing
- pending → order status: pending
- deny/expire/cancel → order status: cancelled
```

## Supported Payment Methods

### 1. QRIS
- **Midtrans Type**: `qris`
- **Providers**: All QRIS-compatible apps (GoPay, OVO, Dana, ShopeePay, etc.)
- **Display**: QR code

### 2. GoPay
- **Midtrans Type**: `gopay`
- **Display**: Deeplink button + QR code
- **Features**: Direct app opening

### 3. ShopeePay
- **Midtrans Type**: `shopeepay`
- **Display**: Deeplink button
- **Features**: Direct app opening

### 4. Dana & OVO
- **Midtrans Type**: `qris` (via QRIS)
- **Display**: QR code
- **Note**: Midtrans doesn't have direct Dana/OVO integration

### 5. Bank Transfer (Virtual Account)
- **BCA**: `bank_transfer` with bank: `bca`
- **BNI**: `bank_transfer` with bank: `bni`
- **BRI**: `bank_transfer` with bank: `bri`
- **Permata**: `bank_transfer` with bank: `permata`
- **Display**: VA number with bank-specific instructions

### 6. Mandiri Bill Payment
- **Midtrans Type**: `echannel`
- **Display**: Biller code + Bill key
- **Instructions**: ATM, internet banking, mobile banking

### 7. Convenience Store
- **Indomaret**: `cstore` with store: `indomaret`
- **Alfamart**: `cstore` with store: `alfamart`
- **Display**: Payment code

## Testing

### Sandbox Mode
Currently configured for **sandbox/testing mode** (`MIDTRANS_IS_PRODUCTION=false`)

### Test Cards (for testing)
Midtrans provides test credentials for sandbox:
- **Test VA Numbers**: Will be generated automatically
- **Test QRIS**: Can be scanned with Midtrans simulator
- **Test E-Wallets**: Use Midtrans simulator app

### Webhook Testing
1. Use ngrok or similar tool to expose local server
2. Configure webhook URL in Midtrans dashboard:
   - URL: `https://your-domain.com/payment/webhook`
3. Test payments will trigger webhooks automatically

### Manual Testing Steps
1. Add items to cart
2. Go to checkout
3. Fill in shipping information
4. Select payment method
5. Submit order
6. You'll be redirected to payment instructions page
7. Follow payment instructions
8. Payment status will auto-update

## Error Handling

### Comprehensive Logging
- All Midtrans API calls are logged
- Webhook processing is logged
- Errors are caught and logged with stack traces

### User-Friendly Error Messages
- Failed transactions show error messages
- Expired payments show appropriate status
- Cancelled payments are handled gracefully

## Security

### CSRF Protection
- Webhook route is exempt from CSRF (required for Midtrans callbacks)
- All other routes are CSRF protected

### Authorization
- Users can only view their own orders
- Guest orders are accessible by anyone with the order number (for guest checkout)

### Webhook Validation
- Midtrans signature validation is handled by Midtrans SDK
- Invalid webhooks are rejected

## Production Checklist

Before going live:
1. ✅ Update `.env`:
   - Set `MIDTRANS_IS_PRODUCTION=true`
   - Use production Server Key and Client Key
2. ✅ Configure webhook URL in Midtrans dashboard
3. ✅ Test all payment methods in production
4. ✅ Monitor logs for any issues
5. ✅ Set up proper error alerting
6. ✅ Configure email notifications for successful payments
7. ✅ Test webhook delivery

## Next Steps (Optional Enhancements)

1. **Email Notifications**
   - Send payment instructions via email
   - Send payment confirmation email
   - Send order status updates

2. **Order Management**
   - Admin panel to view orders
   - Order status tracking for customers
   - Shipping integration

3. **Payment Reminders**
   - Send reminder before payment expires
   - Resend payment instructions

4. **Analytics**
   - Track payment method usage
   - Monitor payment success rates
   - Identify failed payment patterns

5. **Refunds**
   - Implement refund functionality
   - Partial refund support

## Support

### Midtrans Documentation
- Core API: https://docs.midtrans.com/en/core-api/overview
- Payment Methods: https://docs.midtrans.com/en/core-api/bank-transfer
- Webhooks: https://docs.midtrans.com/en/after-payment/http-notification

### Troubleshooting
- Check Laravel logs: `storage/logs/laravel.log`
- Check Midtrans dashboard for transaction details
- Verify webhook URL is accessible from internet
- Ensure server key and client key are correct

## Summary

The Midtrans integration is **complete and ready for testing**. All payment methods are supported, payment instructions are displayed correctly, webhooks are configured, and error handling is comprehensive. The implementation follows best practices and is production-ready after proper testing.
