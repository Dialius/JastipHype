# 🔧 FIX: Disable Hostinger Auto-Deploy

## 🎯 MASALAH

Hostinger punya 2 sistem deployment yang bentrok:
1. **GitHub Actions** (SSH deploy) ✅ Ini yang kita mau
2. **Hostinger Auto-Deploy** (Build di Hostinger) ❌ Ini yang error

Hostinger Auto-Deploy mencoba run `npm install` dan gagal karena peer dependencies.

---

## ✅ SOLUSI: Disable Hostinger Auto-Deploy

### Langkah 1: Login ke hPanel

1. Buka: https://hpanel.hostinger.com
2. Login dengan akun kamu

---

### Langkah 2: Disable Auto-Deploy

1. Klik **Websites** → **Manage** (untuk jastiphype.shop)
2. Scroll ke bawah, cari section **"Git"** atau **"Deployments"**
3. Klik **"Manage"** atau **"Settings"**
4. Cari opsi **"Auto Deploy"** atau **"Automatic Deployment"**
5. **MATIKAN/DISABLE** opsi tersebut
6. Save changes

---

### Langkah 3: Hapus Build Configuration (Jika Ada)

Kalau ada section "Build Configuration":
1. Hapus atau kosongkan **"Build command"**
2. Hapus atau kosongkan **"Install command"**
3. Save

Ini akan membuat Hostinger TIDAK mencoba build npm lagi.

---

### Langkah 4: Trigger Manual Deploy via GitHub Actions

Setelah disable auto-deploy:

1. Buka: https://github.com/Dialius/JastipHype/actions
2. Klik workflow **"Deploy to Hostinger"**
3. Klik tombol **"Run workflow"** (di kanan atas)
4. Pilih branch **"master"**
5. Klik **"Run workflow"**

Ini akan trigger deployment lewat GitHub Actions (SSH), bukan Hostinger auto-deploy.

---

## 🔍 KENAPA INI TERJADI?

**Hostinger Auto-Deploy:**
- Mencoba run `npm install` di server Hostinger
- Gagal karena peer dependency conflicts
- Tidak support `--legacy-peer-deps` flag

**GitHub Actions (SSH Deploy):**
- Build di local/GitHub (sudah berhasil)
- Copy hasil build ke Hostinger via SSH
- Tidak perlu npm install di server
- ✅ Ini yang benar!

---

## 📊 SETELAH DISABLE AUTO-DEPLOY

**Yang akan terjadi:**
1. Push ke GitHub → GitHub Actions jalan ✅
2. GitHub Actions SSH ke Hostinger ✅
3. Copy files, run Laravel commands ✅
4. Hostinger TIDAK mencoba build npm lagi ✅

**Deployment hanya lewat GitHub Actions!**

---

## 🆘 JIKA TIDAK BISA DISABLE AUTO-DEPLOY

### Alternative: Tambahkan .hostingerignore

Buat file `.hostingerignore` di root project:

```
node_modules/
package.json
package-lock.json
vite.config.js
```

Ini akan membuat Hostinger skip npm build.

---

## ✅ CHECKLIST

- [ ] Login ke hPanel
- [ ] Disable Hostinger Auto-Deploy
- [ ] Hapus Build Configuration (jika ada)
- [ ] Trigger manual deploy via GitHub Actions
- [ ] Test website

---

## 📞 JIKA MASIH BINGUNG

Screenshot section "Git" atau "Deployments" di hPanel dan kirim ke saya!

---

**Priority:** HIGH  
**Impact:** Deployment akan berhasil setelah ini!
