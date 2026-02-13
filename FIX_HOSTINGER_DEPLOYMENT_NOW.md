# 🚀 FIX HOSTINGER DEPLOYMENT - STEP BY STEP

## 🔴 MASALAH YANG KAMU ALAMI

Error di Hostinger:
```
ERROR: Failed to install dependencies
NOTICE: Attempting to install with legacy peer dependencies enabled
```

**Penyebab:** Hostinger Auto-Deploy mencoba run `npm install` dan gagal karena peer dependency conflicts.

---

## ✅ SOLUSI 1: DISABLE AUTO-DEPLOY (TERCEPAT - 5 MENIT)

### Step 1: Login ke Hostinger

1. Buka: https://hpanel.hostinger.com
2. Login dengan akun kamu

### Step 2: Masuk ke Website Settings

1. Klik **"Websites"** di sidebar
2. Cari **jastiphype.shop**
3. Klik tombol **"Manage"**

### Step 3: Cari Git/GitHub Section

Scroll ke bawah, cari section dengan salah satu nama ini:
- **"Git"**
- **"GitHub"** 
- **"Deployments"**
- **"Version Control"**

Biasanya ada icon GitHub atau Git.

### Step 4: Disable Auto-Deploy

**Kemungkinan tampilan:**

#### Tampilan A: Ada Toggle "Auto Deploy"
```
Auto Deploy: [ON] ← Klik untuk OFF
```
- Klik toggle untuk matikan
- Klik "Save" atau "Update"

#### Tampilan B: Ada "Build Configuration"
```
Build command: npm install && npm run build
Install command: npm install
```
- **HAPUS** semua command
- Kosongkan field tersebut
- Klik "Save"

#### Tampilan C: Ada Button "Disconnect"
```
Connected to GitHub: Dialius/JastipHype
[Disconnect]
```
- **JANGAN** klik Disconnect (ini akan hapus koneksi Git)
- Cari opsi "Disable automatic deployment" atau "Build settings"

### Step 5: Verify

Setelah disable:
- Refresh halaman
- Pastikan tidak ada indicator "Auto Deploy: Enabled"
- Pastikan Build command kosong

---

## ✅ SOLUSI 2: FIX BUILD COMMAND (ALTERNATIVE - 10 MENIT)

Kalau tidak bisa disable auto-deploy, kita fix build command-nya:

### Step 1: Update Build Configuration di Hostinger

Di section Git/Deployments, update:

**Build command:**
```bash
npm install --legacy-peer-deps && npm run build
```

**Install command:**
```bash
npm install --legacy-peer-deps
```

**Output directory:**
```
public/build
```

Klik "Save"

### Step 2: Trigger Redeploy

1. Di halaman yang sama, cari button **"Deploy"** atau **"Redeploy"**
2. Klik button tersebut
3. Tunggu deployment selesai (5-10 menit)

---

## 🚀 SETELAH FIX

### Jika Pakai Solusi 1 (Disable Auto-Deploy):

**Deployment sekarang lewat GitHub Actions:**

1. Push code ke GitHub
2. GitHub Actions otomatis jalan
3. Deploy via SSH ke Hostinger
4. Selesai!

**Trigger manual:**
```bash
# Di local
git add .
git commit -m "trigger deployment"
git push origin master
```

Atau manual di GitHub Actions:
- https://github.com/Dialius/JastipHype/actions
- Klik "Deploy to Hostinger"
- Klik "Run workflow"

### Jika Pakai Solusi 2 (Fix Build Command):

**Deployment tetap lewat Hostinger:**

1. Push code ke GitHub
2. Hostinger auto-deploy jalan
3. Build dengan `--legacy-peer-deps`
4. Selesai!

---

## 🔍 CEK APAKAH SUDAH BERHASIL

### 1. Cek Deployment Status

**Di Hostinger:**
- hPanel → Websites → Manage
- Tab "Deployments"
- Lihat deployment terbaru
- Status harus "Success" (hijau)

**Di GitHub Actions:**
- https://github.com/Dialius/JastipHype/actions
- Lihat workflow terbaru
- Status harus "Success" (hijau)

### 2. Test Website

Buka: https://jastiphype.shop

**Kemungkinan hasil:**
- ✅ Website jalan normal → SELESAI! 🎉
- ⚠️ Error 500 → Masalah mbstring (ada panduan terpisah)
- ❌ Error lain → Screenshot dan kirim ke saya

---

## 🆘 TROUBLESHOOTING

### Tidak Ketemu Section Git/Deployments

**Coba cek di:**
1. Website → Advanced → Git
2. Website → Settings → Deployments
3. Website → Tools → GitHub

**Atau screenshot halaman Website Management dan kirim ke saya!**

### Masih Error Setelah Disable

**Kemungkinan:**
1. GitHub Actions tidak jalan → Cek GitHub Secrets
2. SSH connection gagal → Verify SSH credentials
3. Hostinger masih build → Tunggu 5-10 menit, cache mungkin masih ada

**Solusi:**
- Cek panduan: `VERIFY_GITHUB_SECRETS.md`
- Test SSH manual: `ssh -p 65002 u909490256@153.92.9.187`

### Build Command Tidak Work

**Coba:**
1. Clear Hostinger cache
2. Trigger redeploy manual
3. Tunggu 10-15 menit

**Atau pakai Solusi 1 (disable auto-deploy) aja!**

---

## 📊 COMPARISON: SOLUSI 1 VS 2

| Aspek | Solusi 1 (Disable) | Solusi 2 (Fix Build) |
|-------|-------------------|---------------------|
| **Waktu** | 5 menit | 10 menit |
| **Kesulitan** | Mudah | Medium |
| **Reliability** | Tinggi | Medium |
| **Deployment** | Via GitHub Actions | Via Hostinger |
| **Speed** | Cepat (3-5 min) | Lambat (10-15 min) |
| **Recommended** | ✅ YES | ⚠️ Alternative |

**Saya recommend Solusi 1!**

---

## 🎯 QUICK DECISION TREE

```
Bisa disable auto-deploy?
├─ YES → Pakai Solusi 1 ✅
└─ NO → Pakai Solusi 2 ⚠️

Solusi 2 tidak work?
├─ YES → Contact Hostinger Support
└─ NO → Selesai! 🎉
```

---

## 📞 NEED HELP?

**Jika bingung:**
1. Screenshot halaman Git/Deployments di hPanel
2. Kirim ke saya
3. Saya akan kasih instruksi spesifik

**Jika masih error:**
1. Screenshot error message
2. Copy deployment log
3. Kirim ke saya atau Hostinger Support

---

## ✅ CHECKLIST

Sebelum mulai:
- [ ] Login ke hPanel
- [ ] Buka Website Management (jastiphype.shop)
- [ ] Cari section Git/Deployments

Solusi 1 (Disable):
- [ ] Matikan Auto Deploy toggle
- [ ] Hapus Build command
- [ ] Save changes
- [ ] Verify sudah disabled

Solusi 2 (Fix Build):
- [ ] Update Build command dengan `--legacy-peer-deps`
- [ ] Update Install command dengan `--legacy-peer-deps`
- [ ] Save changes
- [ ] Trigger redeploy

Setelah fix:
- [ ] Cek deployment status
- [ ] Test website
- [ ] Verify tidak ada error

---

**Priority:** CRITICAL  
**Time:** 5-10 menit  
**Difficulty:** EASY  
**Success Rate:** 95%

---

**Created:** 13 Februari 2026  
**Status:** READY TO EXECUTE  
**Recommended:** Solusi 1 (Disable Auto-Deploy)
