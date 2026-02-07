# 📧 Panduan Sistem Email JastipHype

## Konfigurasi Email

Sistem email JastipHype menggunakan **Hostinger SMTP** dengan 5 alamat email berbeda:

### Alamat Email yang Tersedia:

1. **info@jastiphype.shop** - Email utama untuk kontak umum
2. **admin@jastiphype.shop** - Notifikasi internal dan administrasi
3. **order@jastiphype.shop** - Konfirmasi dan update pesanan
4. **support@jastiphype.shop** - Customer support
5. **noreply@jastiphype.shop** - Email sistem yang tidak perlu dibalas

## Konfigurasi di .env

```env
# Mail - Hostinger SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@jastiphype.shop
MAIL_PASSWORD="XmAJ4!9tmJEt4hE"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@jastiphype.shop"
MAIL_FROM_NAME="JastipHype"

# Additional Email Addresses
MAIL_ADMIN=admin@jastiphype.shop
MAIL_ORDER=order@jastiphype.shop
MAIL_SUPPORT=support@jastiphype.shop
MAIL_NOREPLY=noreply@jastiphype.shop
```

## Cara Menggunakan

### 1. Test Koneksi Email

```bash
php artisan email:test
```

### 2. Kirim Email Konfirmasi Pesanan

```php
use App\Services\EmailService;

$emailService = new EmailService();
$emailService->sendOrderConfirmation($order);
```

### 3. Kirim Email Update Status

```php
$emailService->sendOrderStatusUpdate($order, 'pending', 'processing');
```

### 4. Kirim Email dari Contact Form

```php
$data = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '08123456789',
    'subject' => 'Pertanyaan Produk',
    'message' => 'Halo, saya ingin bertanya...'
];

$emailService->sendContactFormToAdmin($data);
```

## Template Email yang Tersedia

### 1. Order Confirmation (`order-confirmation.blade.php`)
- Dikirim setelah pesanan dibuat
- Berisi detail pesanan lengkap
- Link untuk tracking pesanan

### 2. Order Status Update (`order-status-update.blade.php`)
- Dikirim saat status pesanan berubah
- Menampilkan status lama dan baru
- Informasi tambahan sesuai status

### 3. Contact Form (`contact-form.blade.php`)
- Dikirim ke admin saat ada pesan dari contact form
- Berisi detail pengirim dan pesan

## Integrasi dengan Order Controller

Tambahkan di `OrderController` atau `CheckoutController`:

```php
use App\Services\EmailService;

class CheckoutController extends Controller
{
    protected $emailService;
    
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    public function store(Request $request)
    {
        // ... proses checkout
        
        // Kirim email konfirmasi
        $this->emailService->sendOrderConfirmation($order);
        
        return redirect()->route('orders.show', $order);
    }
}
```

## Integrasi dengan Admin Panel

Tambahkan di admin controller saat update status:

```php
public function updateStatus(Request $request, Order $order)
{
    $oldStatus = $order->status;
    $order->status = $request->status;
    $order->save();
    
    // Kirim email notifikasi
    $this->emailService->sendOrderStatusUpdate($order, $oldStatus, $request->status);
    
    return back()->with('success', 'Status updated and email sent!');
}
```

## Troubleshooting

### Email tidak terkirim?

1. **Cek konfigurasi SMTP:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan email:test
   ```

2. **Cek log error:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Pastikan port 465 tidak diblokir:**
   - Coba ganti ke port 587 dengan encryption TLS
   - Update di .env: `MAIL_PORT=587` dan `MAIL_ENCRYPTION=tls`

4. **Verifikasi kredensial:**
   - Login ke webmail Hostinger
   - Pastikan password benar
   - Cek apakah email aktif

### Email masuk spam?

1. Setup SPF Record di DNS Hostinger
2. Setup DKIM di email settings
3. Tambahkan DMARC record
4. Gunakan domain email yang sama dengan website

## Webmail Access

Akses webmail untuk cek email masuk:
- URL: https://webmail.hostinger.com
- Login dengan email dan password yang sudah dibuat

## Tips & Best Practices

1. **Gunakan Queue untuk Email:**
   ```php
   Mail::to($user)->queue(new OrderConfirmation($order));
   ```

2. **Tambahkan Rate Limiting:**
   - Batasi jumlah email per menit
   - Hindari spam complaints

3. **Monitor Email Logs:**
   - Cek `storage/logs/laravel.log`
   - Setup monitoring untuk failed emails

4. **Backup Email Templates:**
   - Simpan template di version control
   - Test sebelum deploy

## Next Steps

1. ✅ Setup email configuration
2. ✅ Create email templates
3. ✅ Create email service
4. ⏳ Integrate with order system
5. ⏳ Test email sending
6. ⏳ Setup email queue
7. ⏳ Configure DNS records (SPF, DKIM, DMARC)

---

**Dibuat:** {{ date('Y-m-d') }}
**Status:** Ready to Use
