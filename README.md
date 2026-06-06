# 🛍️ JastipHype - Premium Streetwear E-Commerce Platform

<p align="center">
  <img src="public/images/logo/JastipHype_tab-logo.png" alt="JastipHype Logo" width="200">
</p>

<p align="center">
  <strong>Platform jasa titip (jastip) streetwear premium dengan sistem pembayaran terintegrasi dan pelacakan pengiriman real-time</strong>
</p>

<p align="center">
  <a href="https://jastiphype.shop"><img src="https://img.shields.io/badge/Live-jastiphype.shop-black?style=for-the-badge" alt="Live Site"></a>
  <a href="#"><img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel" alt="Laravel"></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.3-purple?style=for-the-badge&logo=php" alt="PHP"></a>
  <a href="#"><img src="https://img.shields.io/badge/MySQL-8.0-blue?style=for-the-badge&logo=mysql" alt="MySQL"></a>
</p>

---

## 📋 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Deployment & Maintenance](#-deployment--maintenance)
- [Tim Pengembang](#-tim-pengembang)
- [Lisensi](#-lisensi)

---

## 🎯 Tentang Project

**JastipHype** adalah platform e-commerce modern yang dikhususkan untuk jual-beli produk streetwear premium seperti Supreme, Off-White, BAPE, Essentials, dan brand hype lainnya. Platform ini menyediakan sistem jastip (jasa titip) yang memudahkan pelanggan untuk mendapatkan produk original limited edition secara aman dan transparan.

---

## 🚀 Fitur Utama

### 👥 Fitur Pelanggan (Customer)
- 🛒 **Shopping Cart & Checkout** - Keranjang belanja interaktif dengan kalkulasi otomatis.
- 💳 **Metode Pembayaran Lengkap** - Terintegrasi dengan Midtrans (Virtual Account, E-Wallet/QRIS, Alfamart/Indomaret).
- 📦 **Real-time Shipping Rate & Tracking** - Estimasi biaya pengiriman otomatis menggunakan RajaOngkir API.
- 🔒 **Sistem Autentikasi** - Keamanan ganda menggunakan enkripsi password modern dan verifikasi email.
- 💬 **Live Chat Support & Ticket** - Layanan aduan dan customer support terintegrasi.

### 🎛️ Fitur Admin (Dashboard)
- 📊 **Analytics & Reports** - Ringkasan penjualan, tren pendapatan, dan statistik pengunjung harian.
- 📦 **Product Management** - Kelola katalog produk, multi-gambar, kategori, dan brand.
- 🏷️ **Brand & Banner Management** - Kelola promosi beranda dan brand streetwear utama.
- 👥 **User Management** - Kontrol data pelanggan dan roles admin.

### 🛡️ Keamanan & Kepatuhan (Security & GDPR)
- **CSRF & XSS Protection** - Perlindungan internal terhadap manipulasi form dan script injeksi.
- **Cookie Consent Banner** - Memenuhi kepatuhan GDPR untuk penggunaan cookie dan data privasi.
- **Data Export & Deletion Request** - Hak pengguna untuk mengunduh portofolio data pribadi atau mengajukan penghapusan akun secara permanen.

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.3+
- **Database**: MySQL 8.0
- **Queue/Cache**: Database Driver

### Frontend
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js, Vanilla JS
- **Build Tool**: Vite

### Third-Party APIs
- **Payment Gateway**: Midtrans (Sandbox & Production)
- **Shipping Calculator**: RajaOngkir
- **SMTP Service**: Hostinger Mail

---

## 💻 Instalasi

### Prerequisites
- PHP >= 8.3 (dengan ekstensi `mbstring`, `openssl`, `pdo`, `xml`, `zip`)
- Composer
- Node.js >= 18.x & NPM

### Langkah-langkah:

1. **Clone Repository**
   ```bash
   git clone https://github.com/Dialius/JastipHype.git
   cd JastipHype
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database**
   Konfigurasikan DB di `.env`, lalu jalankan:
   ```bash
   php artisan migrate --seed
   ```

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Build Asset Frontend**
   ```bash
   # Development
   npm run dev

   # Production Build
   npm run build
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses di browser: `http://localhost:8000`

---

## ⚙️ Konfigurasi

Pastikan Anda melengkapi kredensial berikut di file `.env` Anda:

### Midtrans API
```env
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

### RajaOngkir API
```env
RAJAONGKIR_API_KEY=your_api_key
RAJAONGKIR_TYPE=starter
RAJAONGKIR_ORIGIN=151 # Kode kota asal pengiriman (contoh: 151 untuk Jakarta Barat)
```

---

## 🚀 Deployment & Maintenance

Aplikasi ini menggunakan **GitHub Actions** untuk deployment otomatis ke server Hostinger setiap kali ada perubahan pada branch `master`.

### Struktur Folder Domain Hostinger
Agar aplikasi berjalan lancar di Hostinger tanpa error `403 Forbidden`, struktur folder diatur sebagai berikut:
- **Core Laravel**: Berada di root directory domain (`/home/u909490256/domains/jastiphype.shop`).
- **Web Root**: Berada di folder `public_html/` yang berisi isi dari folder `public/` Laravel dengan `index.php` yang disesuaikan path relatifnya.

### Script Utility yang Tersedia
Untuk memudahkan maintenance langsung dari server, kami menyediakan utility scripts di dalam folder `scripts/`:

1. **`scripts/fix-domain-root.sh`**
   Script ini digunakan untuk menata ulang hak akses folder dan membuat symlink `build` serta `storage` pada `public_html/` agar aset ter-render dengan benar.
   
2. **`scripts/clear-all-cache.sh`**
   Membersihkan seluruh cache konfigurasi, route, view, dan cache aplikasi Laravel secara cepat.

---

## 👥 Tim Pengembang

- **Vinthegreat** - Lead Developer
- **Kiro AI** - Development Assistant

---

## 📄 Lisensi

Project ini dilisensikan di bawah **[MIT License](LICENSE)**.
