# 🚀 QUICK START: Auto-Deployment dengan GitHub Actions

## 📋 Overview

Panduan cepat untuk setup auto-deployment dari GitHub ke Hostinger menggunakan GitHub Actions. Setiap kali Anda push code ke GitHub, otomatis ter-deploy ke server production.

---

## ⚡ QUICK STEPS (5 Menit)

### 1️⃣ Generate SSH Key (Di Windows)

```powershell
# Buka PowerShell di folder project
cd path\to\JastipHype

# Jalankan script generator
powershell -ExecutionPolicy Bypass -File generate-ssh-key-for-github-actions.ps1
```

Script akan:
- ✅ Generate SSH key pair
- ✅ Tampilkan public & private key
- ✅ Copy ke clipboard (optional)
- ✅ Berikan instruksi lengkap

### 2️⃣ Add Public Key ke Hostinger

**Via SSH (Recommended):**
```bash
# Login ke Hostinger
ssh u909490256@jastiphype.shop -p 65002

# Jalankan setup script
cd /home/u909490256/domains/jastiphype.shop
bash setup-github-actions-ssh.sh

# Paste public key saat diminta
# Script akan otomatis setup semuanya
```

**Via File Manager:**
1. Login ke hPanel: https://hpanel.hostinger.com
2. File Manager → Navigate ke `/home/u909490256/.ssh/`
3. Edit `authorized_keys`
4. Paste public key di baris paling bawah
5. Save

### 3️⃣ Add Private Key ke GitHub

1. Buka: https://github.com/Dialius/JastipHype/settings/secrets/actions
2. Update secret `SSH_PRIVATE_KEY`
3. Paste private key (yang TANPA .pub)
4. Save

### 4️⃣ Test Deployment

1. Buka: https://github.com/Dialius/JastipHype/actions
2. Klik "Deploy to Hostinger"
3. Klik "Run workflow"
4. Monitor progress

---

## ✅ Verification

Setelah deployment berhasil:

```bash
# Test SSH dari local
ssh -i github-actions-key -p 65002 u909490256@jastiphype.shop

# Test website
curl -I https://jastiphype.shop
# Should return: HTTP/2 200
```

---

## 🔄 Daily Workflow

Setelah setup, workflow harian Anda:

```bash
# 1. Edit code di local
# 2. Commit changes
git add .
git commit -m "Update feature X"

# 3. Push ke GitHub
git push origin master

# 4. GitHub Actions otomatis deploy!
# 5. Cek di: https://github.com/Dialius/JastipHype/actions
# 6. Website updated: https://jastiphype.shop
```

**Tidak perlu SSH manual lagi!** 🎉

---

## 🆘 Troubleshooting Cepat

### ❌ Error: "Permission denied (publickey)"

**Fix:**
```bash
# Re-run setup di server
ssh u909490256@jastiphype.shop -p 65002
bash /home/u909490256/domains/jastiphype.shop/setup-github-actions-ssh.sh
```

### ❌ Website masih 404 setelah deploy

**Fix:**
```bash
# Run fix script
ssh u909490256@jastiphype.shop -p 65002
cd /home/u909490256/domains/jastiphype.shop
bash fix-404-error.sh
```

### ❌ Deployment timeout

**Fix:**
- Cek GitHub Actions logs
- Pastikan server tidak down
- Verify SSH_HOST, SSH_PORT di GitHub Secrets

---

## 📚 Dokumentasi Lengkap

Untuk panduan detail, lihat:
- **FIX_GITHUB_ACTIONS_SSH.md** - Setup SSH authentication lengkap
- **FIX_404_ERROR.md** - Fix website 404 error
- **FRESH_DEPLOYMENT_GUIDE.md** - Fresh deployment dari awal

---

## 🎯 Expected Result

Setelah setup berhasil:

✅ Push code → GitHub Actions trigger otomatis  
✅ Deployment selesai dalam 2-5 menit  
✅ Website updated tanpa manual SSH  
✅ Logs tersedia di GitHub Actions  
✅ Email notification (optional)  

---

**Happy Deploying! 🚀**
