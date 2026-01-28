# 📧 SMTP Gmail Setup - Quick Guide

## Step-by-Step Setup Gmail SMTP

### 1️⃣ Enable 2-Factor Authentication

1. Buka browser, login ke Gmail kamu
2. Kunjungi: https://myaccount.google.com/security
3. Scroll ke bagian **"Signing in to Google"**
4. Klik **"2-Step Verification"**
5. Ikuti instruksi untuk mengaktifkan 2FA (biasanya pakai nomor HP)

### 2️⃣ Generate App Password

1. Setelah 2FA aktif, kembali ke: https://myaccount.google.com/security
2. Scroll ke **"Signing in to Google"**
3. Klik **"App passwords"** (muncul setelah 2FA aktif)
4. Pilih:
   - **Select app**: Mail
   - **Select device**: Windows Computer (atau Other)
5. Klik **"Generate"**
6. **COPY** password 16 digit yang muncul
   - Contoh: `abcd efgh ijkl mnop`
   - Hapus spasi saat paste ke .env

### 3️⃣ Update File .env

Buka file `.env` di root project, cari bagian `MAIL_` dan update:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="JastipHype"
```

**Contoh Lengkap:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=jastiphype@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=jastiphype@gmail.com
MAIL_FROM_NAME="JastipHype"
```

### 4️⃣ Clear Cache Laravel

Jalankan command ini di terminal:

```bash
php artisan config:clear
php artisan cache:clear
```

### 5️⃣ Test Email

Buka terminal dan jalankan:

```bash
php artisan tinker
```

Lalu paste code ini (ganti email tujuan):

```php
Mail::raw('Test email from JastipHype', function($msg) {
    $msg->to('your-test-email@gmail.com')->subject('Test Email');
});
```

Tekan Enter. Jika berhasil, cek inbox email tujuan.

---

## ⚠️ Troubleshooting

### Email tidak terkirim?

**1. Cek App Password**
- Pastikan tidak ada spasi
- Copy ulang dari Gmail
- Generate app password baru jika perlu

**2. Cek 2FA**
- Pastikan 2-Step Verification sudah aktif
- App Password hanya muncul setelah 2FA aktif

**3. Cek Port & Encryption**
- Port 587 dengan TLS (recommended)
- Atau coba Port 465 dengan SSL:
  ```env
  MAIL_PORT=465
  MAIL_ENCRYPTION=ssl
  ```

**4. Cek Laravel Log**
```bash
# Buka file ini untuk lihat error detail
storage/logs/laravel.log
```

**5. Gmail Limits**
- Free Gmail: 500 emails/day
- Jika over limit, tunggu 24 jam atau upgrade ke Google Workspace

---

## 🔒 Security Tips

1. **Jangan commit .env ke Git**
   - File `.env` sudah ada di `.gitignore`
   - Jangan share app password ke orang lain

2. **Gunakan Environment Variables**
   - Untuk production, gunakan environment variables server
   - Jangan hardcode password di code

3. **Monitor Email Activity**
   - Cek Gmail security activity secara berkala
   - Revoke app password jika tidak digunakan

---

## 📝 Alternative: Mailtrap (Development)

Untuk testing di development, bisa pakai Mailtrap (tidak kirim email real):

1. Daftar di: https://mailtrap.io (gratis)
2. Buat inbox baru
3. Copy credentials
4. Update .env:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@jastiphype.com
MAIL_FROM_NAME="JastipHype"
```

---

## ✅ Checklist Setup

- [ ] 2FA Gmail sudah aktif
- [ ] App Password sudah di-generate
- [ ] File .env sudah di-update
- [ ] Config cache sudah di-clear
- [ ] Test email berhasil terkirim
- [ ] Register user baru → email verification terkirim
- [ ] Forgot password → OTP terkirim

---

## 🎯 Next Steps

Setelah SMTP setup berhasil, test fitur-fitur ini:

1. **Email Verification**
   - Register user baru
   - Cek email untuk verification link

2. **Forgot Password**
   - Klik "Forgot Password" di login page
   - Cek email untuk OTP code

3. **Change Password**
   - Login → Profile → Change Password
   - Cek email untuk OTP code

---

## 📞 Need Help?

Jika masih ada masalah:
1. Cek file `EMAIL_VERIFICATION_SYSTEM.md` untuk dokumentasi lengkap
2. Cek Laravel log di `storage/logs/laravel.log`
3. Google error message yang muncul
4. Pastikan internet connection stabil

---

**Happy Coding! 🚀**
