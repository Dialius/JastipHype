# 🕐 Cron Jobs Setup Guide for Hostinger

## 📋 Overview

Karena menggunakan shared hosting, cron jobs harus diatur melalui cPanel/hPanel.

---

## 🔧 Setup via hPanel (Hostinger)

### 1. Login ke hPanel
- Visit: https://hpanel.hostinger.com
- Login dengan credentials Hostinger Anda

### 2. Navigate to Cron Jobs
- Di dashboard, cari menu "Advanced"
- Klik "Cron Jobs"

### 3. Add Cron Jobs

Tambahkan 3 cron jobs berikut:

#### Cron Job 1: Process GDPR Exports (Every Hour)

```
Type: Common Settings → Once Per Hour (atau Custom)
Minute: 0
Hour: *
Day: *
Month: *
Weekday: *

Command:
cd /home/u909490256/domains/jastiphype.shop && /usr/bin/php artisan gdpr:process-exports >> /dev/null 2>&1
```

**Atau jika path PHP berbeda:**
```
cd /home/u909490256/domains/jastiphype.shop && php artisan gdpr:process-exports >> /dev/null 2>&1
```

#### Cron Job 2: Cleanup Expired Exports (Daily at Midnight)

```
Type: Common Settings → Once Per Day (atau Custom)
Minute: 0
Hour: 0
Day: *
Month: *
Weekday: *

Command:
cd /home/u909490256/domains/jastiphype.shop && /usr/bin/php artisan gdpr:cleanup-exports >> /dev/null 2>&1
```

#### Cron Job 3: Security Cleanup (Daily at 1 AM)

```
Type: Common Settings → Once Per Day (atau Custom)
Minute: 0
Hour: 1
Day: *
Month: *
Weekday: *

Command:
cd /home/u909490256/domains/jastiphype.shop && /usr/bin/php artisan security:cleanup >> /dev/null 2>&1
```

---

## 🧪 Testing Cron Jobs

### Test Manually via SSH

```bash
# Test GDPR export processing
cd ~/domains/jastiphype.shop
php artisan gdpr:process-exports

# Test cleanup
php artisan gdpr:cleanup-exports

# Test security cleanup
php artisan security:cleanup
```

### Check Cron Job Logs

Hostinger biasanya menyimpan cron logs di:
```bash
# Via SSH
cat ~/logs/cron.log
# atau
cat ~/domains/jastiphype.shop/storage/logs/laravel.log
```

---

## 📊 Monitoring

### Check if Cron Jobs are Running

1. **Via Admin Dashboard:**
   - Login as admin
   - Visit: https://jastiphype.shop/admin/system
   - Check "Last Cron Run" timestamps

2. **Via Database:**
```bash
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop
php artisan tinker --execute="echo App\Models\DataExportRequest::late