# 🔧 CARA DISABLE HOSTINGER AUTO-DEPLOY (STEP BY STEP)

## 🎯 MASALAH YANG KAMU ALAMI

Dari screenshot, terlihat:
- **Status:** Proses build deployment gagal
- **Error:** Failed to install dependencies
- **Penyebab:** Hostinger Auto-Deploy mencoba run `npm install` dan gagal

**Ini BUKAN GitHub Actions!** Ini sistem deploy Hostinger sendiri yang bentrok!

---

## ✅ SOLUSI: DISABLE AUTO-DEPLOY DI HOSTINGER

### Step 1: Login ke hPanel

1. Buka: https://hpanel.hostinger.com
2. Login dengan akun kamu

---

### Step 2: Masuk ke Website Management

1. Di dashboard, klik **"Websites"**
2. Cari website **jastiphype.shop**
3. Klik tombol **"Manage"**

---

### Step 3: Cari Section Git/Deployment

Scroll ke bawah, cari section dengan nama salah satu dari:
- **"Git"**
- **"GitHub"**
- **"Deployments"**
- **"Auto Deploy"**

Biasanya ada icon GitHub atau Git di section ini.

---

### Step 4: Disable Auto-Deploy

Di section tersebut, kamu akan lihat:

**Option A: Ada Toggle "Auto Deploy"**
- Matikan toggle tersebut
- Klik "Save" atau "Update"

**Option B: Ada Button "Disconnect" atau "Remove"**
- Klik button tersebut
- Confirm disconnect

**Option C: Ada Settings/Configuration**
- Klik "Settings" atau icon gear
- Cari opsi "Automatic Deployment" atau "Auto Deploy"
- Disable/Off
- Save changes

---

### Step 5: Hapus Build Configuration (PENTING!)

Kalau ada section **"Build Configuration"** atau **"Build Settings"**:

1. Cari field:
   - **"Build command"** → Kosongkan atau hapus
   - **"Install command"** → Kosongkan atau hapus
   - **"Output directory"** → Kosongkan atau hapus

2. Klik **"Save"** atau **"Update"**

Ini akan membuat Hostinger TIDAK mencoba build npm lagi!

---

### Step 6: Verify - Cek Apakah Sudah Disabled

Setelah disable, coba:

1. Refresh halaman hPanel
2. Cek section Git/Deployment
3. Pastikan tidak ada indicator "Auto Deploy: Enabled"
4. Pastikan tidak ada build command yang tersimpan

---

## 🚀 SETELAH DISABLE AUTO-DEPLOY

### Cara Deploy Sekarang:

**Hanya lewat GitHub Actions!**

1. Push code ke GitHub
2. GitHub Actions otomatis jalan
3. Deploy via SSH ke Hostinger
4. Selesai!

**Hostinger tidak akan mencoba build lagi!**

---

## 📊 VISUAL GUIDE

### Kemungkinan Tampilan di hPanel:

**Scenario 1: Ada Toggle**
```
┌─────────────────────────────────┐
│ GitHub Integration              │
│                                 │
│ Repository: Dialius/JastipHype  │
│ Branch: master                  │
│                                 │
│ Auto Deploy: [ON] ← MATIKAN INI │
│                                 │
│ [Save Changes]                  │
└─────────────────────────────────┘
```

**Scenario 2: Ada Build Settings**
```
┌─────────────────────────────────┐
│ Build Configuration             │
│                                 │
│ Build command:                  │
│ [npm install && npm run build]  │
│ ← HAPUS INI                     │
│                                 │
│ Install command:                │
│ [npm install]                   │
│ ← HAPUS INI                     │
│                                 │
│ [Save]                          │
└─────────────────────────────────┘
```

**Scenario 3: Ada Disconnect Button**
```
┌─────────────────────────────────┐
│ Connected to GitHub             │
│                                 │
│ Repository: Dialius/JastipHype  │
│                                 │
│ [Disconnect] ← KLIK INI         │
└─────────────────────────────────┘
```

---

## 🆘 JIKA TIDAK KETEMU SECTION GIT/DEPLOYMENT

### Alternative 1: Cari di Menu Lain

Coba cek di:
1. **Website** → **Advanced** → **Git**
2. **Website** → **Settings** → **Deployments**
3. **Website** → **Tools** → **GitHub**

### Alternative 2: Gunakan .hostingerignore

Kalau benar-benar tidak ketemu, buat file `.hostingerignore`:

```bash
# Di local machine
echo "node_modules/" > .hostingerignore
echo "package.json" >> .hostingerignore
echo "package-lock.json" >> .hostingerignore
echo "vite.config.js" >> .hostingerignore

git add .hostingerignore
git commit -m "chore: add .hostingerignore to prevent Hostinger auto-build"
git push origin master
```

Ini akan membuat Hostinger skip npm build.

### Alternative 3: Contact Hostinger Support

Kalau masih bingung, hubungi Hostinger Support:

**Template pesan:**
```
Hi,

I need to disable the automatic deployment/build system for my website jastiphype.shop.

Currently, Hostinger is trying to run npm install automatically and it's failing with peer dependency errors.

I want to use GitHub Actions for deployment instead (via SSH).

Can you please:
1. Disable auto-deploy for jastiphype.shop
2. Remove any build commands (npm install, npm run build)
3. Keep only SSH access enabled

Thank you!
```

---

## ✅ CARA VERIFY SUDAH BERHASIL

### Test 1: Push Dummy Commit

```bash
# Di local machine
echo "# test" >> README.md
git add README.md
git commit -m "test: verify auto-deploy disabled"
git push origin master
```

**Yang harus terjadi:**
- ✅ GitHub Actions jalan (cek di GitHub)
- ❌ Hostinger TIDAK mencoba build (cek di hPanel Deployments)

### Test 2: Cek hPanel Deployments

1. Buka hPanel → Websites → Manage
2. Klik tab **"Deployments"**
3. Seharusnya TIDAK ada deployment baru dari Hostinger
4. Deployment hanya dari GitHub Actions (via SSH)

---

## 🎯 EXPECTED RESULT

**Setelah disable auto-deploy:**

1. ✅ Push ke GitHub → GitHub Actions jalan
2. ✅ GitHub Actions SSH ke Hostinger
3. ✅ Deploy berhasil tanpa npm build error
4. ❌ Hostinger TIDAK mencoba build sendiri

**Deployment hanya lewat GitHub Actions!**

---

## 📞 BUTUH BANTUAN?

**Jika masih bingung:**
1. Screenshot section Git/Deployment di hPanel
2. Kirim screenshot ke saya
3. Saya akan kasih instruksi spesifik

**Jika tidak ketemu section tersebut:**
1. Screenshot seluruh halaman Website Management
2. Kirim ke saya
3. Saya akan bantu cari

---

## ⏱️ ESTIMASI WAKTU

- **Disable auto-deploy:** 2-3 menit
- **Verify:** 1 menit
- **Test deployment:** 5 menit

**Total:** ~10 menit dan masalah selesai!

---

**Priority:** CRITICAL  
**Impact:** Setelah ini deployment akan berhasil!  
**Difficulty:** EASY (tinggal matikan toggle)

---

**Created:** 13 Februari 2026  
**Status:** READY TO EXECUTE
