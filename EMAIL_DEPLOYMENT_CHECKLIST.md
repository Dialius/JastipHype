# ✅ Email System Deployment Checklist

## Pre-Deployment

- [x] Setup 5 email accounts di Hostinger
  - admin@jastiphype.shop
  - info@jastiphype.shop
  - order@jastiphype.shop
  - support@jastiphype.shop
  - noreply@jastiphype.shop

- [x] Update .env dengan konfigurasi SMTP Hostinger
- [x] Create email templates (order-confirmation, order-status-update, contact-form)
- [x] Create EmailService untuk handle email sending
- [x] Create ContactController untuk contact form
- [x] Test email connection locally

## Deployment Steps

### 1. Update Environment Variables di Vercel

```bash
# Di Vercel Dashboard > Settings > Environment Variables
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@jastiphype.shop
MAIL_PASSWORD=XmAJ4!9tmJEt4hE
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@jastiphype.shop
MAIL_FROM_NAME=JastipHype

MAIL_ADMIN=admin@jastiphype.shop
MAIL_ORDER=order@jastiphype.shop
MAIL_SUPPORT=support@jastiphype.shop
MAIL_NOREPLY=noreply@jastiphype.shop
```

### 2. Deploy ke Production

```bash
git add .
git commit -m "feat: implement email system with Hostinger SMTP"
git push origin main
```

### 3. Test di Production

1. Akses: https://jastiphype.shop/contact
2. Isi form dan kirim
3. Cek email masuk di admin@jastiphype.shop

### 4. Integrate dengan Order System

Update `CheckoutController` atau `OrderController`:

```php
use App\Services\EmailService;

protected $emailService;

public function __construct(EmailService $emailService)
{
    $this->emailService = $emailService;
}

// Setelah order dibuat
$this->emailService->sendOrderConfirmation($order);
```

### 5. Setup DNS Records (Optional tapi Recommended)

Di Hostinger DNS Management, tambahkan:

**SPF Record:**
```
Type: TXT
Name: @
Value: v=spf1 include:_spf.hostinger.com ~all
```

**DKIM Record:**
- Cek di Hostinger Email > Settings > DKIM
- Copy record yang diberikan
- Tambahkan ke DNS

**DMARC Record:**
```
Type: TXT
Name: _dmarc
Value: v=DMARC1; p=none; rua=mailto:admin@jastiphype.shop
```

## Post-Deployment Testing

### Test Email Sending

```bash
# SSH ke server atau run di local
php artisan email:test
```

### Test Contact Form

1. Buka https://jastiphype.shop/contact
2. Isi form dengan data test
3. Submit
4. Cek email di admin@jastiphype.shop

### Test Order Confirmation (setelah integrasi)

1. Buat test order
2. Cek email di customer email
3. Verify template tampil dengan benar

## Monitoring

### Check Email Logs

```bash
# Di server
tail -f storage/logs/laravel.log | grep -i mail
```

### Check Failed Emails

```php
// Tambahkan di EmailService
Log::error('Failed to send email', [
    'type' => 'order_confirmation',
    'order_id' => $order->id,
    'error' => $e->getMessage()
]);
```

## Troubleshooting

### Email tidak terkirim?

1. **Cek SMTP credentials:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test koneksi:**
   ```bash
   php artisan email:test
   ```

3. **Cek firewall:**
   - Port 465 harus terbuka
   - Atau gunakan port 587 dengan TLS

4. **Cek email quota:**
   - Login ke Hostinger
   - Cek usage di Email section

### Email masuk spam?

1. Setup SPF, DKIM, DMARC records
2. Warm up email (kirim bertahap)
3. Avoid spam trigger words
4. Use proper email formatting

## Next Features

- [ ] Email queue untuk performance
- [ ] Email templates untuk newsletter
- [ ] Email tracking (open rate, click rate)
- [ ] Automated email campaigns
- [ ] Email preferences untuk user

## Support

Jika ada masalah:
1. Cek logs: `storage/logs/laravel.log`
2. Test email: `php artisan email:test`
3. Verify SMTP settings di .env
4. Contact Hostinger support jika SMTP issue

---

**Status:** Ready for Production ✅
**Last Updated:** {{ date('Y-m-d') }}
