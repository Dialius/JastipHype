# Cara Test Integrasi Midtrans

## Status Implementasi
✅ Midtrans Core API sudah terintegrasi dengan baik
✅ Semua payment method sudah ditest dan berfungsi (QRIS, Bank Transfer, GoPay, Mandiri Bill, Convenience Store)
✅ Database sudah diperbaiki
✅ Routes sudah dikonfigurasi
✅ Webhook handler sudah siap

## Langkah-Langkah Testing

### 1. Persiapan
Pastikan:
- Server Laravel sudah running (`php artisan serve`)
- Database sudah di-migrate
- File `.env` sudah dikonfigurasi dengan Midtrans credentials

### 2. Test Checkout Flow

#### A. Tambah Produk ke Cart
1. Buka halaman produk
2. Pilih size dan quantity
3. Klik "Add to Cart"
4. Pastikan produk masuk ke cart

#### B. Proses Checkout
1. Klik "Checkout" dari cart
2. Isi form checkout:
   - **Email**: Masukkan email valid
   - **Name**: Masukkan nama lengkap
   - **Phone**: Masukkan nomor telepon (format: 08xxx atau 628xxx)
   - **Address**: Masukkan alamat lengkap
   - **Province**: Pilih provinsi
   - **City**: Pilih kota (akan muncul setelah pilih provinsi)
   - **Subdistrict**: Pilih kecamatan
   - **Postal Code**: Akan terisi otomatis
3. Pilih metode pembayaran:
   - **QRIS**: Langsung klik (tidak perlu pilih detail)
   - **E-Wallet**: Klik, lalu pilih (GoPay/Dana/OVO/ShopeePay)
   - **Virtual Account**: Klik, lalu pilih bank (BCA/Mandiri/BNI/BRI/Permata)
   - **Convenience Store**: Klik, lalu pilih (Indomaret/Alfamart)
4. Klik "PLACE ORDER"

#### C. Halaman Pembayaran
Setelah klik "PLACE ORDER", Anda akan diarahkan ke halaman pembayaran yang menampilkan:
- Order number
- Total pembayaran
- Status pembayaran
- Instruksi pembayaran sesuai metode yang dipilih
- Detail pesanan

### 3. Troubleshooting

#### Masalah: Tidak redirect ke halaman pembayaran setelah klik "PLACE ORDER"

**Kemungkinan Penyebab:**
1. **Error validasi form** - Cek apakah semua field sudah terisi dengan benar
2. **Error JavaScript** - Buka browser console (F12) dan cek error
3. **Error server** - Cek log Laravel

**Cara Cek Error:**

##### A. Cek Browser Console
1. Tekan F12 di browser
2. Buka tab "Console"
3. Coba submit form lagi
4. Lihat apakah ada error JavaScript

##### B. Cek Laravel Log
```bash
# Windows (PowerShell)
Get-Content storage/logs/laravel.log -Tail 50

# Atau buka file langsung
notepad storage/logs/laravel.log
```

##### C. Cek Network Request
1. Buka browser DevTools (F12)
2. Buka tab "Network"
3. Submit form
4. Cari request ke `/checkout` (method: POST)
5. Klik request tersebut
6. Lihat:
   - **Headers**: Pastikan status code 200 atau 302 (redirect)
   - **Response**: Lihat response dari server
   - **Payload**: Lihat data yang dikirim

#### Masalah: Error "Column not found"
Sudah diperbaiki dengan migration terbaru. Jika masih terjadi:
```bash
php artisan migrate:fresh --seed
```
⚠️ **WARNING**: Ini akan menghapus semua data!

#### Masalah: Error Midtrans API
Cek:
1. Server Key sudah benar di `.env`
2. Koneksi internet stabil
3. Midtrans sandbox sedang tidak maintenance

### 4. Test Payment Methods di Sandbox

#### QRIS
- QR code akan ditampilkan
- Gunakan Midtrans Simulator app untuk scan
- Atau gunakan test QR scanner

#### Bank Transfer (Virtual Account)
- VA number akan ditampilkan
- Untuk test, gunakan Midtrans Dashboard > Transactions > pilih transaction > klik "Set to Settlement"

#### GoPay
- Deeplink akan ditampilkan
- Klik untuk buka app (jika di mobile)
- Atau scan QR code
- Untuk test, gunakan Midtrans Simulator

#### Mandiri Bill Payment
- Bill Key dan Biller Code akan ditampilkan
- Untuk test, gunakan Midtrans Dashboard untuk set status

#### Convenience Store
- Payment code akan ditampilkan
- Untuk test, gunakan Midtrans Dashboard untuk set status

### 5. Test Webhook

#### Setup Ngrok (untuk test di local)
```bash
# Install ngrok
# Download dari https://ngrok.com/download

# Run ngrok
ngrok http 8000

# Copy URL yang diberikan (contoh: https://abc123.ngrok.io)
```

#### Configure Webhook di Midtrans Dashboard
1. Login ke Midtrans Dashboard (sandbox)
2. Go to Settings > Configuration
3. Set Payment Notification URL: `https://your-ngrok-url.ngrok.io/payment/webhook`
4. Save

#### Test Webhook
1. Buat transaksi baru
2. Di Midtrans Dashboard, pilih transaction
3. Klik "Set to Settlement" atau "Set to Expire"
4. Cek Laravel log untuk melihat webhook diterima
5. Cek database untuk melihat status payment dan order terupdate

### 6. Simulasi Pembayaran di Sandbox

#### Cara 1: Via Midtrans Dashboard
1. Login ke https://dashboard.sandbox.midtrans.com
2. Go to Transactions
3. Cari transaction berdasarkan order number
4. Klik transaction
5. Klik "Set to Settlement" untuk simulasi pembayaran sukses
6. Atau klik "Set to Expire" untuk simulasi pembayaran expired

#### Cara 2: Via Midtrans Simulator App
1. Download Midtrans Simulator app (Android/iOS)
2. Scan QR code yang ditampilkan
3. Confirm payment di app

### 7. Monitoring

#### Cek Status Payment
- Halaman pembayaran akan auto-refresh setiap 10 detik
- Atau klik tombol "Cek Status Pembayaran"
- Status akan update otomatis ketika payment berhasil

#### Cek Database
```sql
-- Cek orders
SELECT * FROM orders ORDER BY created_at DESC LIMIT 5;

-- Cek payments
SELECT * FROM payments ORDER BY created_at DESC LIMIT 5;

-- Cek order dengan payment
SELECT o.order_number, o.status, p.transaction_status, p.payment_type
FROM orders o
LEFT JOIN payments p ON o.id = p.order_id
ORDER BY o.created_at DESC
LIMIT 5;
```

## Error Messages dan Solusi

### "SQLSTATE[42S22]: Column not found"
**Solusi**: Run migration fix
```bash
php artisan migrate
```

### "Failed to create payment: ..."
**Solusi**: 
1. Cek Midtrans credentials di `.env`
2. Cek koneksi internet
3. Cek Laravel log untuk detail error

### "Order not found"
**Solusi**: Pastikan order sudah dibuat sebelum akses halaman payment

### "Unauthorized access"
**Solusi**: Pastikan user yang login adalah pemilik order (atau guest dengan order number yang benar)

## Tips Testing

1. **Gunakan email test**: test@example.com
2. **Gunakan phone test**: 08123456789
3. **Gunakan alamat test**: Jl. Test No. 123
4. **Clear cache** jika ada masalah: `php artisan cache:clear`
5. **Clear log** sebelum test: `echo "" > storage/logs/laravel.log`
6. **Monitor log** saat test: `Get-Content storage/logs/laravel.log -Wait -Tail 20`

## Checklist Testing

- [ ] Cart berfungsi (add, update, delete)
- [ ] Checkout form validation berfungsi
- [ ] Province/City/Subdistrict dropdown berfungsi
- [ ] Payment method selection berfungsi
- [ ] Form submit berhasil
- [ ] Redirect ke halaman payment
- [ ] Payment instructions ditampilkan dengan benar
- [ ] QR code ditampilkan (untuk QRIS)
- [ ] VA number ditampilkan (untuk Bank Transfer)
- [ ] Payment code ditampilkan (untuk Convenience Store)
- [ ] Auto-refresh status berfungsi
- [ ] Manual check status berfungsi
- [ ] Webhook diterima dan diproses
- [ ] Order status terupdate setelah payment
- [ ] Payment status terupdate setelah payment

## Next Steps Setelah Testing Berhasil

1. Test semua payment methods
2. Test webhook dengan ngrok
3. Test error scenarios (expired, cancelled, failed)
4. Test dengan berbagai browser
5. Test responsive design (mobile, tablet)
6. Prepare untuk production:
   - Update `.env` dengan production credentials
   - Set `MIDTRANS_IS_PRODUCTION=true`
   - Configure production webhook URL
   - Test di production environment
