# 🛍️ JastipHype - Premium Streetwear E-Commerce Platform

<p align="center">
  <img src="public/images/logo/JastipHype_logo.png" alt="JastipHype Logo" width="200">
</p>

<p align="center">
  <strong>Platform jastip streetwear premium dengan sistem pembayaran terintegrasi dan tracking pengiriman real-time</strong>
</p>

<p align="center">
  <a href="https://jastiphype.shop"><img src="https://img.shields.io/badge/Live-jastiphype.shop-blue?style=for-the-badge" alt="Live Site"></a>
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
- [Deployment](#-deployment)
- [Dokumentasi](#-dokumentasi)
- [Troubleshooting](#-troubleshooting)
- [Tim Pengembang](#-tim-pengembang)

---

## 🎯 Tentang Project

**JastipHype** adalah platform e-commerce modern yang dikhususkan untuk jual-beli produk streetwear premium seperti Supreme, Off-White, BAPE, dan brand hype lainnya. Platform ini menyediakan sistem jastip (jasa titip) yang memudahkan customer untuk mendapatkan produk limited edition dari berbagai negara.

### 🌟 Keunggulan

- ✅ **Sistem Pembayaran Terintegrasi** - Midtrans payment gateway dengan multiple payment methods
- ✅ **Real-time Shipping Tracking** - Integrasi RajaOngkir API untuk cek ongkir otomatis
- ✅ **Admin Dashboard Lengkap** - Monitoring sistem, manajemen produk, dan analytics
- ✅ **Email Notification System** - Automated email untuk order confirmation dan updates
- ✅ **Google OAuth Login** - Login cepat dengan akun Google
- ✅ **Responsive Design** - Mobile-first design dengan Tailwind CSS
- ✅ **SEO Optimized** - Meta tags dan structured data untuk better ranking

---

## 🚀 Fitur Utama

### 👥 Customer Features

- 🛒 **Shopping Cart** - Add to cart, quantity adjustment, dan checkout seamless
- 🔍 **Advanced Search & Filter** - Cari produk berdasarkan brand, kategori, dan harga
- 💳 **Multiple Payment Methods** - Credit card, bank transfer, e-wallet (via Midtrans)
- 📦 **Order Tracking** - Real-time order status dan shipping tracking
- ⭐ **Product Reviews** - Rating dan review produk dari customer lain
- 🔔 **Wishlist** - Simpan produk favorit untuk dibeli nanti
- 👤 **User Profile** - Manage alamat, order history, dan personal info

### 🎛️ Admin Features

- 📊 **Dashboard Analytics** - Sales report, visitor stats, dan revenue tracking
- 📦 **Product Management** - CRUD produk dengan multiple images dan variants
- 🏷️ **Category & Brand Management** - Organize produk dengan kategori dan brand
- 🎨 **Banner Management** - Manage homepage banners dan promotional content
- 👥 **User Management** - Manage customer accounts dan admin roles
- 📧 **Email Templates** - Customizable email templates untuk notifications
- 🔧 **System Monitor** - Real-time system health, database stats, dan error logs
- 📤 **Export/Import** - Bulk import/export produk via Excel

### 🔐 Security Features

- 🔒 **CSRF Protection** - Laravel built-in CSRF protection
- 🛡️ **XSS Prevention** - Input sanitization dan output escaping
- 🔑 **Password Hashing** - Bcrypt password hashing
- 🚫 **Rate Limiting** - API rate limiting untuk prevent abuse
- 📝 **Activity Logging** - Track user activities dan admin actions

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.3+
- **Database**: MySQL 8.0
- **Cache**: Database cache driver
- **Queue**: Database queue driver

### Frontend
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js, Vanilla JS
- **Build Tool**: Vite
- **Icons**: Heroicons

### Third-Party Services
- **Payment Gateway**: Midtrans
- **Shipping API**: RajaOngkir
- **Email**: Hostinger SMTP
- **OAuth**: Google OAuth 2.0

### DevOps
- **Hosting**: Hostinger Shared Hosting
- **CI/CD**: GitHub Actions
- **Version Control**: Git & GitHub

---

## 💻 Instalasi

### Prerequisites

Pastikan sistem Anda sudah terinstall:
- PHP >= 8.3
- Composer
- MySQL >= 8.0
- Node.js >= 18.x
- NPM atau Yarn

### Step 1: Clone Repository

```bash
git clone https://github.com/Dialius/JastipHype.git
cd JastipHype
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE jastiphype;
EXIT;

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### Step 5: Storage Setup

```bash
# Create storage link (for local development)
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### Step 6: Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Step 7: Run Application

```bash
# Start development server
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

---

## ⚙️ Konfigurasi

### Database Configuration

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jastiphype
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Midtrans Configuration

Daftar di [Midtrans](https://midtrans.com) dan dapatkan credentials:

```env
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

### RajaOngkir Configuration

Daftar di [RajaOngkir](https://rajaongkir.com) dan dapatkan API key:

```env
RAJAONGKIR_API_KEY=your_api_key
RAJAONGKIR_TYPE=starter
RAJAONGKIR_ORIGIN=151
```

### Email Configuration

Untuk Hostinger SMTP:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@jastiphype.shop
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@jastiphype.shop
MAIL_FROM_NAME="JastipHype"
```

### Google OAuth Configuration

1. Buat project di [Google Cloud Console](https://console.cloud.google.com)
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add authorized redirect URI: `https://yourdomain.com/auth/google/callback`

```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback
```

---

## 🚀 Deployment

### Hostinger Deployment

#### 1. Setup SSH Key

Lihat panduan lengkap: [GENERATE_SSH_KEY_WINDOWS.md](GENERATE_SSH_KEY_WINDOWS.md)

```bash
# Generate SSH key
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

# Add to GitHub
cat ~/.ssh/id_rsa.pub
```

#### 2. Setup GitHub Actions

File `.github/workflows/deploy.yml` sudah dikonfigurasi untuk auto-deployment.

Tambahkan secrets di GitHub repository:
- `SSH_HOST`: Hostname server Hostinger
- `SSH_USERNAME`: Username SSH
- `SSH_PRIVATE_KEY`: Private key SSH
- `SSH_PORT`: Port SSH (default: 65002)

#### 3. Deploy

```bash
# Push ke master branch untuk trigger deployment
git push origin master
```

Deployment akan otomatis:
- Pull latest code
- Install dependencies
- Run migrations
- Clear & rebuild cache
- Copy files ke public_html

### Manual Deployment

Jika GitHub Actions gagal, deploy manual via SSH:

```bash
# Login SSH
ssh username@hostname -p 65002

# Masuk ke folder project
cd domains/yourdomain.com

# Pull latest code
git pull origin master

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Copy to public_html
cp -rf public/* public_html/
```

---

## 📚 Dokumentasi

### Panduan Lengkap

- [📧 Email System Guide](EMAIL_SYSTEM_GUIDE.md) - Setup dan konfigurasi email system
- [🔧 Hostinger Setup](HOSTINGER_SETUP.md) - Setup hosting di Hostinger
- [🔐 Private Repo Setup](HOSTINGER_PRIVATE_REPO_SETUP.md) - Setup private repository
- [🚨 403 Error Fix](HOSTINGER_403_FIX.md) - Fix 403 forbidden error
- [🎨 Custom Error Pages](CUSTOM_ERROR_PAGES.md) - Customize error pages

### Quick Reference

#### Admin Login

```
URL: https://jastiphype.shop/admin/login
Default Admin:
- Email: admin@jastiphype.shop
- Password: (set during seeding)
```

#### API Endpoints

```
POST /api/checkout - Process checkout
GET  /api/shipping/cost - Calculate shipping cost
POST /api/payment/notification - Midtrans callback
```

#### Artisan Commands

```bash
# Clear all cache
php artisan optimize:clear

# Rebuild cache
php artisan optimize

# Check system health
php artisan system:check

# Export products
php artisan products:export

# Import products
php artisan products:import {file}
```

---

## 🔧 Troubleshooting

### Error 500 - Internal Server Error

```bash
# Check error log
tail -50 storage/logs/laravel.log

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
```

### Images Not Showing

```bash
# Run image migration script
php migrate-private-to-public.php

# Fix database paths
php fix-database-paths.php

# Verify FILESYSTEM_DISK
grep FILESYSTEM_DISK .env
# Should be: FILESYSTEM_DISK=public
```

### Database Connection Error

```bash
# Check database credentials in .env
cat .env | grep DB_

# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Emergency Fix

Jika ada masalah serius, jalankan nuclear fix:

```bash
bash nuclear-fix.sh
```

Script ini akan:
- Backup .env
- Clear semua cache
- Rebuild config
- Set permissions
- Test database connection

---

## 👥 Tim Pengembang

### Project Lead
- **Vinthegreat** - Full Stack Developer

### Contributors
- **Kiro AI** - Development Assistant & Code Review

---

## 📄 License

Project ini adalah proprietary software. Semua hak cipta dilindungi.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Midtrans](https://midtrans.com) - Payment Gateway
- [RajaOngkir](https://rajaongkir.com) - Shipping API
- [Hostinger](https://hostinger.com) - Web Hosting

---

## 📞 Support

Jika ada pertanyaan atau butuh bantuan:

- 📧 Email: info@jastiphype.shop
- 🌐 Website: [jastiphype.shop](https://jastiphype.shop)
- 💬 GitHub Issues: [Create Issue](https://github.com/Dialius/JastipHype/issues)

---

<p align="center">
  Made with ❤️ by <strong>Vinthegreat</strong>
</p>

<p align="center">
  <sub>© 2026 JastipHype. All rights reserved.</sub>
</p>
