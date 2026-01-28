# Email Verification & Password Reset System

## 📋 Overview

Sistem lengkap untuk:
1. **Email Verification** - User harus verifikasi email setelah register
2. **Forgot Password** - Reset password dengan OTP via email
3. **Change Password** - Ganti password di profile dengan verifikasi OTP

---

## 🔧 Setup SMTP Gmail

### Step 1: Enable 2-Factor Authentication di Gmail

1. Buka [Google Account Security](https://myaccount.google.com/security)
2. Scroll ke "Signing in to Google"
3. Klik "2-Step Verification" dan aktifkan

### Step 2: Generate App Password

1. Setelah 2FA aktif, kembali ke [Security Settings](https://myaccount.google.com/security)
2. Scroll ke "Signing in to Google"
3. Klik "App passwords"
4. Pilih:
   - **App**: Mail
   - **Device**: Windows Computer (atau sesuai device kamu)
5. Klik "Generate"
6. **Copy password 16 digit** yang muncul (contoh: `abcd efgh ijkl mnop`)

### Step 3: Update .env File

Buka file `.env` dan update bagian mail:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="JastipHype"
```

**Contoh:**
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

**⚠️ PENTING:**
- Jangan ada spasi di app password
- Gunakan email yang sama untuk `MAIL_USERNAME` dan `MAIL_FROM_ADDRESS`
- Pastikan 2FA sudah aktif sebelum generate app password

---

## 🗄️ Database Migration

Jalankan migration untuk menambahkan field OTP:

```bash
php artisan migrate
```

Migration akan menambahkan field:
- `password_reset_otp` - OTP untuk forgot password
- `password_reset_otp_expires_at` - Expiry time (60 menit)
- `password_change_otp` - OTP untuk change password di profile
- `password_change_otp_expires_at` - Expiry time (10 menit)
- `pending_password` - Password baru yang menunggu verifikasi

---

## 📧 Fitur 1: Email Verification

### Flow:
1. User register → Email verification dikirim otomatis
2. User **TIDAK BISA LOGIN** sebelum verifikasi
3. User klik link di email → Email terverifikasi
4. User bisa login

### Routes:
- `GET /email/verify` - Halaman notice (harus login)
- `GET /email/verify/{id}/{hash}` - Verify email (signed URL)
- `POST /email/verification-notification` - Resend verification email

### Testing:
```bash
# 1. Register user baru
# 2. Cek email untuk verification link
# 3. Klik link di email
# 4. Login dengan akun yang sudah diverifikasi
```

---

## 🔑 Fitur 2: Forgot Password

### Flow:
1. User klik "Forgot Password" di login page
2. Input email → OTP dikirim ke email
3. User input OTP + password baru
4. Password berhasil direset → Auto login

### Routes:
- `GET /forgot-password` - Form input email
- `POST /forgot-password` - Kirim OTP ke email
- `GET /reset-password` - Form input OTP + password baru
- `POST /reset-password` - Verify OTP dan reset password
- `POST /resend-otp` - Resend OTP

### OTP Details:
- **Format**: 6 digit angka (contoh: 123456)
- **Expiry**: 60 menit
- **Email Template**: `resources/views/emails/password-reset-otp.blade.php`

### Testing:
```bash
# 1. Buka /forgot-password
# 2. Input email yang terdaftar
# 3. Cek email untuk OTP code
# 4. Input OTP + password baru
# 5. Otomatis login setelah berhasil
```

---

## 🔐 Fitur 3: Change Password (Profile)

### Flow:
1. User di profile page → Klik "Change Password"
2. Input password lama + password baru
3. OTP dikirim ke email
4. User input OTP
5. Password berhasil diubah

### Routes:
- `POST /profile/password/request-otp` - Request OTP untuk change password
- `POST /profile/password/verify-otp` - Verify OTP dan change password

### OTP Details:
- **Format**: 6 digit angka
- **Expiry**: 10 menit
- **Email Template**: `resources/views/emails/password-change-otp.blade.php`

### Update Profile Page:

Tambahkan form ini di `resources/views/profile/index.blade.php` di tab Security:

```blade
{{-- Change Password with OTP --}}
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Change Password</h3>
    
    @if(session('otp_sent'))
        {{-- OTP Verification Form --}}
        <form method="POST" action="{{ route('profile.password.verify-otp') }}" class="space-y-4">
            @csrf
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg mb-4">
                {{ session('otp_sent') }}
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Enter OTP Code</label>
                <input 
                    type="text" 
                    name="otp" 
                    required
                    maxlength="6"
                    pattern="[0-9]{6}"
                    class="w-full px-4 py-2 border rounded-lg text-center text-2xl tracking-widest font-bold"
                    placeholder="000000"
                >
                @error('otp')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="btn-primary">
                Verify & Change Password
            </button>
        </form>
    @else
        {{-- Request OTP Form --}}
        <form method="POST" action="{{ route('profile.password.request-otp') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input 
                    type="password" 
                    name="current_password" 
                    required
                    class="w-full px-4 py-2 border rounded-lg"
                >
                @error('current_password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input 
                    type="password" 
                    name="password" 
                    required
                    minlength="8"
                    class="w-full px-4 py-2 border rounded-lg"
                >
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    required
                    minlength="8"
                    class="w-full px-4 py-2 border rounded-lg"
                >
            </div>
            
            <button type="submit" class="btn-primary">
                Send OTP to Email
            </button>
        </form>
    @endif
</div>
```

---

## 🧪 Testing Checklist

### Email Verification:
- [ ] Register user baru
- [ ] Email verification terkirim
- [ ] User tidak bisa login sebelum verifikasi
- [ ] Klik link di email berhasil verifikasi
- [ ] User bisa login setelah verifikasi
- [ ] Resend verification email berfungsi

### Forgot Password:
- [ ] Form forgot password bisa diakses
- [ ] OTP terkirim ke email
- [ ] OTP valid 60 menit
- [ ] Input OTP salah = error
- [ ] Input OTP benar + password baru = berhasil
- [ ] Auto login setelah reset
- [ ] Resend OTP berfungsi

### Change Password:
- [ ] Form change password di profile
- [ ] Input password lama salah = error
- [ ] OTP terkirim ke email
- [ ] OTP valid 10 menit
- [ ] Input OTP salah = error
- [ ] Input OTP benar = password berubah

---

## 🔍 Troubleshooting

### Email tidak terkirim:

1. **Cek .env configuration**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test SMTP connection**
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

3. **Cek Laravel log**
   ```
   storage/logs/laravel.log
   ```

4. **Common Issues:**
   - App password salah → Generate ulang
   - 2FA belum aktif → Aktifkan dulu
   - Port blocked → Coba port 465 dengan `MAIL_ENCRYPTION=ssl`
   - Gmail security → Allow "Less secure app access" (tidak recommended)

### OTP expired:

- Forgot Password: 60 menit
- Change Password: 10 menit
- Request OTP baru jika expired

### User tidak bisa login:

- Pastikan email sudah diverifikasi
- Cek `email_verified_at` di database
- Resend verification email

---

## 📁 File Structure

```
app/
├── Http/Controllers/Auth/
│   ├── EmailVerificationController.php
│   ├── ForgotPasswordController.php
│   ├── LoginController.php (updated)
│   └── RegisterController.php (updated)
├── Http/Controllers/
│   └── ProfileController.php (updated)
├── Mail/
│   ├── PasswordResetOtpMail.php
│   └── PasswordChangeOtpMail.php
└── Models/
    └── User.php (updated - implements MustVerifyEmail)

resources/views/
├── auth/
│   ├── forgot-password.blade.php
│   ├── reset-password.blade.php
│   ├── verify-email.blade.php
│   └── login.blade.php (updated)
└── emails/
    ├── password-reset-otp.blade.php
    └── password-change-otp.blade.php

database/migrations/
└── 2026_01_28_024322_add_otp_fields_to_users_table.php

routes/
└── web.php (updated)
```

---

## 🎨 Email Templates

Email templates sudah di-styling dengan:
- Responsive design
- Professional layout
- Brand colors (Black & Gold)
- Security warnings
- Clear CTA

Templates bisa di-customize di:
- `resources/views/emails/password-reset-otp.blade.php`
- `resources/views/emails/password-change-otp.blade.php`

---

## 🔒 Security Features

1. **OTP Expiration**
   - Forgot Password: 60 menit
   - Change Password: 10 menit

2. **Rate Limiting**
   - Verification email: 6 requests per minute
   - Login attempts: 5 attempts per minute

3. **Signed URLs**
   - Email verification menggunakan signed URL
   - Tidak bisa dimanipulasi

4. **Password Hashing**
   - Menggunakan bcrypt
   - Pending password di-hash sebelum disimpan

5. **CSRF Protection**
   - Semua form protected dengan CSRF token

---

## 📝 Notes

- Pastikan queue worker running untuk email async (opsional)
- Gunakan Mailtrap untuk testing di development
- Untuk production, consider menggunakan SendGrid/Mailgun untuk reliability
- Monitor email sending rate untuk avoid Gmail limits (500 emails/day untuk free account)

---

## ✅ Selesai!

Sistem email verification dan password reset sudah lengkap dan siap digunakan.
