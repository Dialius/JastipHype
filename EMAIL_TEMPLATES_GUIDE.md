# 📧 Email Templates Guide - JastipHype

## 📋 Overview

Sistem email lengkap dengan template professional untuk semua kebutuhan JastipHype.

---

## ✅ SMTP Configuration - SUDAH DISETUP!

### Current Settings (.env):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=jastiphype@gmail.com
MAIL_PASSWORD=pxluzdwoqzwwdxgo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=jastiphype@gmail.com
MAIL_FROM_NAME=JastipHype
```

✅ **Status**: Ready to use!

---

## 📨 Email Templates Available

### 1. **Welcome Email** 🎉
**File**: `resources/views/emails/welcome.blade.php`  
**Mail Class**: `app/Mail/WelcomeMail.php`  
**Trigger**: Setelah user verifikasi email

**Features**:
- Welcome message dengan nama user
- List fitur yang bisa digunakan
- CTA button "Start Shopping Now"
- Social media links
- Professional design dengan brand colors

**Usage**:
```php
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(new WelcomeMail($user->name));
```

---

### 2. **Email Verification** ✉️
**File**: Laravel default (auto-generated)  
**Trigger**: Saat user register

**Features**:
- Signed URL untuk security
- Verification link
- Expires setelah beberapa waktu

**Auto-sent**: Ya, otomatis terkirim saat register

---

### 3. **Password Reset OTP** 🔑
**File**: `resources/views/emails/password-reset-otp.blade.php`  
**Mail Class**: `app/Mail/PasswordResetOtpMail.php`  
**Trigger**: User klik "Forgot Password"

**Features**:
- 6-digit OTP code
- Valid 60 menit
- Security warnings
- Professional layout

**Usage**:
```php
use App\Mail\PasswordResetOtpMail;

Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user->name));
```

---

### 4. **Password Change OTP** 🔐
**File**: `resources/views/emails/password-change-otp.blade.php`  
**Mail Class**: `app/Mail/PasswordChangeOtpMail.php`  
**Trigger**: User request change password di profile

**Features**:
- 6-digit OTP code
- Valid 10 menit
- Security warnings
- Clear instructions

**Usage**:
```php
use App\Mail\PasswordChangeOtpMail;

Mail::to($user->email)->send(new PasswordChangeOtpMail($otp, $user->name));
```

---

### 5. **Order Confirmation** 📦
**File**: `resources/views/emails/order-confirmation.blade.php`  
**Mail Class**: `app/Mail/OrderConfirmationMail.php`  
**Trigger**: Setelah order berhasil dibuat

**Features**:
- Order number & details
- Item list dengan harga
- Shipping information
- Payment details
- Track order button
- Complete order summary

**Usage**:
```php
use App\Mail\OrderConfirmationMail;

// Load order with relationships
$order = Order::with(['items.product', 'payment'])->find($orderId);

Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
```

**Integration Point**: Tambahkan di `CheckoutController` atau `PaymentController` setelah order berhasil.

---

### 6. **Payment Success** 💳
**File**: `resources/views/emails/payment-success.blade.php`  
**Mail Class**: `app/Mail/PaymentSuccessMail.php`  
**Trigger**: Setelah payment berhasil (webhook Midtrans)

**Features**:
- Success checkmark design
- Amount paid
- Payment details
- Transaction ID
- Next steps information
- View order button

**Usage**:
```php
use App\Mail\PaymentSuccessMail;

$order = Order::with('payment')->find($orderId);

Mail::to($order->customer_email)->send(new PaymentSuccessMail($order));
```

**Integration Point**: Tambahkan di `WebhookController` saat payment status = success.

---

## 🎨 Email Design Features

### Brand Colors:
- **Primary**: Black (#000)
- **Accent**: Gold (#d4af37)
- **Success**: Green (#28a745)
- **Warning**: Yellow (#ffc107)

### Design Elements:
- ✅ Responsive design (mobile-friendly)
- ✅ Professional layout
- ✅ Clear typography
- ✅ Brand consistency
- ✅ Call-to-action buttons
- ✅ Security badges
- ✅ Social proof elements

### Email Structure:
1. **Header** - Logo & title
2. **Content** - Main message
3. **CTA** - Action buttons
4. **Footer** - Company info & links

---

## 🔧 Integration Guide

### 1. Welcome Email (Already Integrated)
Location: `app/Http/Controllers/Auth/EmailVerificationController.php`

```php
// Already added in verify() method
if ($request->user()->markEmailAsVerified()) {
    event(new Verified($request->user()));
    
    // Send welcome email
    Mail::to($request->user()->email)->send(new \App\Mail\WelcomeMail($request->user()->name));
}
```

### 2. Order Confirmation Email
Add to: `app/Http/Controllers/CheckoutController.php`

```php
// After order is created successfully
use App\Mail\OrderConfirmationMail;

// In process() method, after order creation:
try {
    $order = Order::with(['items.product', 'payment'])->find($order->id);
    Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
} catch (\Exception $e) {
    \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
}
```

### 3. Payment Success Email
Add to: `app/Http/Controllers/WebhookController.php`

```php
// In handle() method, when payment is successful:
use App\Mail\PaymentSuccessMail;

if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
    // Update order status
    $order->update(['status' => 'paid']);
    
    // Send payment success email
    try {
        $order = Order::with('payment')->find($order->id);
        Mail::to($order->customer_email)->send(new PaymentSuccessMail($order));
    } catch (\Exception $e) {
        \Log::error('Failed to send payment success email: ' . $e->getMessage());
    }
}
```

---

## 🧪 Testing Emails

### Method 1: Tinker (Quick Test)
```bash
php artisan tinker
```

```php
// Test Welcome Email
Mail::to('test@example.com')->send(new \App\Mail\WelcomeMail('John Doe'));

// Test Password Reset OTP
Mail::to('test@example.com')->send(new \App\Mail\PasswordResetOtpMail('123456', 'John Doe'));

// Test Password Change OTP
Mail::to('test@example.com')->send(new \App\Mail\PasswordChangeOtpMail('654321', 'John Doe'));
```

### Method 2: Real Flow Testing
1. **Email Verification**: Register user baru
2. **Forgot Password**: Klik "Forgot Password" di login
3. **Change Password**: Login → Profile → Change Password
4. **Order Confirmation**: Buat order baru (setelah integrasi)
5. **Payment Success**: Complete payment (setelah integrasi)

---

## 📊 Email Sending Status

| Email Type | Status | Auto-Send | Manual Integration Needed |
|------------|--------|-----------|---------------------------|
| Email Verification | ✅ Active | Yes | No |
| Welcome Email | ✅ Active | Yes | No |
| Password Reset OTP | ✅ Active | Yes | No |
| Password Change OTP | ✅ Active | Yes | No |
| Order Confirmation | ✅ Ready | No | Yes - CheckoutController |
| Payment Success | ✅ Ready | No | Yes - WebhookController |

---

## 🔍 Troubleshooting

### Email tidak terkirim?

1. **Cek SMTP credentials**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test connection**
   ```bash
   php artisan tinker
   Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
   ```

3. **Cek Laravel log**
   ```
   storage/logs/laravel.log
   ```

4. **Verify Gmail settings**
   - 2FA aktif
   - App password benar
   - No spaces in password

### Email masuk spam?

1. **Add SPF record** (jika punya domain)
2. **Warm up email** - Kirim sedikit dulu
3. **Avoid spam words** - Sudah di-handle di template
4. **Use professional content** - Sudah di-design professional

---

## 📝 Customization Guide

### Change Email Colors:
Edit inline styles di file `.blade.php`:
```html
<!-- Primary color -->
<div style="background: #000;">

<!-- Accent color -->
<span style="color: #d4af37;">
```

### Change Email Content:
Edit file di `resources/views/emails/`:
- `welcome.blade.php`
- `order-confirmation.blade.php`
- `payment-success.blade.php`
- `password-reset-otp.blade.php`
- `password-change-otp.blade.php`

### Add New Email Template:

1. **Create Mail Class**:
   ```bash
   php artisan make:mail NewEmailMail
   ```

2. **Create Blade Template**:
   ```
   resources/views/emails/new-email.blade.php
   ```

3. **Send Email**:
   ```php
   Mail::to($user->email)->send(new NewEmailMail($data));
   ```

---

## 🚀 Production Checklist

- [x] SMTP configured
- [x] Email templates created
- [x] Welcome email integrated
- [x] Password reset working
- [x] Email verification working
- [ ] Order confirmation integrated (add to CheckoutController)
- [ ] Payment success integrated (add to WebhookController)
- [ ] Test all email flows
- [ ] Monitor email delivery rate
- [ ] Setup email tracking (optional)

---

## 📞 Support

Jika ada masalah:
1. Cek `storage/logs/laravel.log`
2. Test SMTP connection
3. Verify Gmail app password
4. Check email queue (jika pakai queue)

---

## 🎯 Next Steps

1. **Integrate Order Confirmation**:
   - Add to CheckoutController
   - Test dengan order baru

2. **Integrate Payment Success**:
   - Add to WebhookController
   - Test dengan Midtrans webhook

3. **Monitor Email Delivery**:
   - Track open rates (optional)
   - Monitor bounce rates
   - Check spam reports

4. **Optional Enhancements**:
   - Add email queue for better performance
   - Add email tracking
   - Add unsubscribe link
   - Add email preferences

---

**All email templates are ready to use! 🎉**
