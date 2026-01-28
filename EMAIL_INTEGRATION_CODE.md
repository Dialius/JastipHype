# 📧 Email Integration Code - Copy & Paste

## 🎯 Quick Integration untuk Order & Payment Emails

### 1️⃣ Order Confirmation Email

**File**: `app/Http/Controllers/CheckoutController.php`

**Tambahkan di bagian atas file (use statements)**:
```php
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
```

**Tambahkan setelah order berhasil dibuat** (di method `process()`):

Cari bagian ini:
```php
// After order is created successfully
$order = Order::create([...]);
```

Tambahkan code ini setelahnya:
```php
// Send order confirmation email
try {
    $orderWithRelations = Order::with(['items.product', 'payment'])->find($order->id);
    Mail::to($order->customer_email)->send(new OrderConfirmationMail($orderWithRelations));
    \Log::info('Order confirmation email sent to: ' . $order->customer_email);
} catch (\Exception $e) {
    \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
    // Don't fail the order if email fails
}
```

---

### 2️⃣ Payment Success Email

**File**: `app/Http/Controllers/WebhookController.php`

**Tambahkan di bagian atas file (use statements)**:
```php
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;
```

**Tambahkan saat payment berhasil** (di method `handle()`):

Cari bagian ini:
```php
if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
    // Update payment status
    $payment->update([
        'status' => 'paid',
        'paid_at' => now()
    ]);
    
    // Update order status
    $order->update(['status' => 'paid']);
```

Tambahkan code ini setelahnya:
```php
    // Send payment success email
    try {
        $orderWithPayment = Order::with('payment')->find($order->id);
        Mail::to($order->customer_email)->send(new PaymentSuccessMail($orderWithPayment));
        \Log::info('Payment success email sent to: ' . $order->customer_email);
    } catch (\Exception $e) {
        \Log::error('Failed to send payment success email: ' . $e->getMessage());
        // Don't fail the webhook if email fails
    }
}
```

---

## 🧪 Testing Code

### Test Order Confirmation Email:
```bash
php artisan tinker
```

```php
// Get latest order
$order = \App\Models\Order::with(['items.product', 'payment'])->latest()->first();

// Send email
Mail::to('your-email@gmail.com')->send(new \App\Mail\OrderConfirmationMail($order));

// Check if sent
echo "Email sent!";
```

### Test Payment Success Email:
```bash
php artisan tinker
```

```php
// Get order with payment
$order = \App\Models\Order::with('payment')->where('status', 'paid')->latest()->first();

// Send email
Mail::to('your-email@gmail.com')->send(new \App\Mail\PaymentSuccessMail($order));

// Check if sent
echo "Email sent!";
```

---

## 📋 Complete Integration Checklist

### CheckoutController.php:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Services\MidtransService;
use App\Mail\OrderConfirmationMail;  // ← ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;  // ← ADD THIS

class CheckoutController extends Controller
{
    // ... existing code ...
    
    public function process(Request $request)
    {
        // ... validation code ...
        
        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                // ... order data ...
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    // ... item data ...
                ]);
            }
            
            // Create payment
            $payment = Payment::create([
                // ... payment data ...
            ]);
            
            // Clear cart
            $cart->items()->delete();
            
            DB::commit();
            
            // ↓↓↓ ADD THIS CODE ↓↓↓
            // Send order confirmation email
            try {
                $orderWithRelations = Order::with(['items.product', 'payment'])->find($order->id);
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($orderWithRelations));
                Log::info('Order confirmation email sent to: ' . $order->customer_email);
            } catch (\Exception $e) {
                Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
            // ↑↑↑ END OF NEW CODE ↑↑↑
            
            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                // ... rest of response ...
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            // ... error handling ...
        }
    }
}
```

### WebhookController.php:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Mail\PaymentSuccessMail;  // ← ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;  // ← ADD THIS

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // ... existing webhook code ...
        
        $transactionStatus = $notification->transaction_status;
        
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // Update payment
            $payment->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);
            
            // Update order
            $order->update(['status' => 'paid']);
            
            // ↓↓↓ ADD THIS CODE ↓↓↓
            // Send payment success email
            try {
                $orderWithPayment = Order::with('payment')->find($order->id);
                Mail::to($order->customer_email)->send(new PaymentSuccessMail($orderWithPayment));
                Log::info('Payment success email sent to: ' . $order->customer_email);
            } catch (\Exception $e) {
                Log::error('Failed to send payment success email: ' . $e->getMessage());
            }
            // ↑↑↑ END OF NEW CODE ↑↑↑
        }
        
        // ... rest of webhook code ...
    }
}
```

---

## ✅ Verification Steps

After integration:

1. **Test Order Flow**:
   - Buat order baru
   - Cek email inbox
   - Verify order confirmation email diterima

2. **Test Payment Flow**:
   - Complete payment
   - Cek email inbox
   - Verify payment success email diterima

3. **Check Logs**:
   ```bash
   # Check if emails are being sent
   tail -f storage/logs/laravel.log
   ```

4. **Monitor**:
   - Check email delivery rate
   - Monitor for errors
   - Verify email content

---

## 🔍 Troubleshooting

### Email tidak terkirim setelah order?

1. **Check logs**:
   ```bash
   tail -f storage/logs/laravel.log | grep "email"
   ```

2. **Verify order has email**:
   ```php
   $order = Order::latest()->first();
   echo $order->customer_email; // Should not be null
   ```

3. **Test email manually**:
   ```bash
   php artisan tinker
   $order = Order::with(['items.product', 'payment'])->latest()->first();
   Mail::to('test@example.com')->send(new \App\Mail\OrderConfirmationMail($order));
   ```

### Email format rusak?

- Pastikan order di-load dengan relationships:
  ```php
  Order::with(['items.product', 'payment'])->find($orderId)
  ```

### Email masuk spam?

- Normal untuk email pertama kali
- Minta user check spam folder
- Setelah beberapa kali, Gmail akan recognize

---

## 📊 Email Flow Diagram

```
User Register
    ↓
Email Verification Sent (auto)
    ↓
User Click Link
    ↓
Welcome Email Sent (auto)
    ↓
User Shop & Checkout
    ↓
Order Created
    ↓
Order Confirmation Email (manual integration)
    ↓
User Pay
    ↓
Payment Success
    ↓
Payment Success Email (manual integration)
```

---

## 🎯 Priority Integration

**High Priority** (Do First):
1. ✅ Email Verification (already done)
2. ✅ Welcome Email (already done)
3. ✅ Password Reset (already done)
4. 🔲 Order Confirmation (add to CheckoutController)
5. 🔲 Payment Success (add to WebhookController)

**Low Priority** (Optional):
- Shipping notification
- Order status updates
- Newsletter
- Promotional emails

---

**Ready to integrate! Copy code above and paste to respective controllers.** 🚀
