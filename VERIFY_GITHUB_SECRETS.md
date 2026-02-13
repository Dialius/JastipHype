# 🔐 VERIFY & UPDATE GITHUB SECRETS

## 🎯 SSH CREDENTIALS YANG BENAR

Berdasarkan info yang kamu berikan:

```
Host: 153.92.9.187
Port: 65002
Username: u909490256
```

**Command SSH yang benar:**
```bash
ssh -p 65002 u909490256@153.92.9.187
```

---

## ✅ CEK GITHUB SECRETS

### Step 1: Buka GitHub Repository Settings

1. Buka: https://github.com/Dialius/JastipHype
2. Klik tab **"Settings"** (paling kanan)
3. Di sidebar kiri, klik **"Secrets and variables"** → **"Actions"**

---

### Step 2: Verify Secrets

Kamu harus punya 4 secrets:

| Secret Name | Value yang Benar |
|-------------|------------------|
| `SSH_HOST` | `153.92.9.187` |
| `SSH_PORT` | `65002` |
| `SSH_USERNAME` | `u909490256` |
| `SSH_PRIVATE_KEY` | (Private key dari Hostinger) |

---

### Step 3: Update Secrets (Jika Salah)

Untuk setiap secret yang salah:

1. Klik nama secret
2. Klik **"Update"** atau **"Edit"**
3. Paste value yang benar
4. Klik **"Update secret"**

---

## 🔑 CARA DAPAT SSH PRIVATE KEY

### Option 1: Dari Hostinger hPanel

1. Login ke hPanel: https://hpanel.hostinger.com
2. Klik **"Websites"** → **"Manage"** (jastiphype.shop)
3. Scroll ke **"SSH Access"** atau **"Advanced"** → **"SSH"**
4. Klik **"Generate SSH Key"** atau **"View SSH Key"**
5. Copy **PRIVATE KEY** (yang panjang, mulai dari `-----BEGIN RSA PRIVATE KEY-----`)

### Option 2: Dari File Local (Jika Sudah Pernah Generate)

Kalau kamu sudah pernah generate SSH key untuk Hostinger:

**Windows:**
```bash
# Cek di folder .ssh
type %USERPROFILE%\.ssh\hostinger_rsa
```

**Linux/Mac:**
```bash
cat ~/.ssh/hostinger_rsa
```

Copy seluruh isi file (termasuk header dan footer).

---

## 📋 FORMAT SSH PRIVATE KEY YANG BENAR

Private key harus seperti ini:

```
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA...
(banyak baris random characters)
...
-----END RSA PRIVATE KEY-----
```

**PENTING:**
- Harus ada header `-----BEGIN RSA PRIVATE KEY-----`
- Harus ada footer `-----END RSA PRIVATE KEY-----`
- Tidak boleh ada spasi di awal/akhir
- Copy SEMUA baris

---

## 🧪 TEST SSH CONNECTION

Setelah update secrets, test SSH connection:

### Test Manual (Di Local)

```bash
# Test connection
ssh -p 65002 u909490256@153.92.9.187

# Kalau berhasil, kamu akan masuk ke server
# Ketik 'exit' untuk keluar
```

**Jika diminta password:**
- Berarti SSH key belum di-setup di Hostinger
- Atau SSH key yang di GitHub Secrets salah

---

## 🚀 TRIGGER DEPLOYMENT SETELAH UPDATE

Setelah update secrets:

### Option 1: Manual Trigger

1. Buka: https://github.com/Dialius/JastipHype/actions
2. Klik workflow **"Deploy to Hostinger"**
3. Klik **"Run workflow"** (kanan atas)
4. Pilih branch **"master"**
5. Klik **"Run workflow"**

### Option 2: Push Dummy Commit

```bash
# Di local machine
echo "# test deployment" >> README.md
git add README.md
git commit -m "test: trigger deployment after secrets update"
git push origin master
```

---

## 🔍 CEK APAKAH DEPLOYMENT BERHASIL

### 1. Buka GitHub Actions

https://github.com/Dialius/JastipHype/actions

### 2. Lihat Workflow Terbaru

- 🟡 **Kuning (Running)** = Sedang jalan
- 🟢 **Hijau (Success)** = Berhasil! ✅
- 🔴 **Merah (Failed)** = Gagal, lihat error

### 3. Jika Failed, Cek Error

Klik workflow yang failed → Klik "Deploy via SSH" → Lihat error message

**Common errors:**

#### Error: "Permission denied (publickey)"
**Penyebab:** SSH key salah atau tidak di-setup di Hostinger  
**Solusi:** Update `SSH_PRIVATE_KEY` secret dengan key yang benar

#### Error: "Connection refused"
**Penyebab:** Host atau Port salah  
**Solusi:** Verify `SSH_HOST` dan `SSH_PORT` secrets

#### Error: "Host key verification failed"
**Penyebab:** First time connection  
**Solusi:** Workflow akan retry otomatis, atau tambahkan `script_stop: true` di workflow

---

## 📊 CHECKLIST

Sebelum trigger deployment:

- [ ] `SSH_HOST` = `153.92.9.187`
- [ ] `SSH_PORT` = `65002`
- [ ] `SSH_USERNAME` = `u909490256`
- [ ] `SSH_PRIVATE_KEY` = (Private key lengkap dengan header/footer)
- [ ] Test SSH connection manual berhasil
- [ ] Hostinger Auto-Deploy sudah disabled

Setelah trigger:

- [ ] GitHub Actions workflow jalan
- [ ] Workflow status hijau (success)
- [ ] Website accessible: https://jastiphype.shop
- [ ] Tidak ada error 500

---

## 🆘 TROUBLESHOOTING

### Tidak Punya SSH Private Key

**Solusi:** Generate baru di Hostinger:

1. hPanel → SSH Access
2. Generate new SSH key pair
3. Download private key
4. Copy ke GitHub Secrets
5. Public key otomatis di-add ke Hostinger

### SSH Key Tidak Work

**Solusi:** Regenerate:

1. Hapus SSH key lama di Hostinger
2. Generate baru
3. Update GitHub Secrets
4. Test connection

### Masih Gagal Setelah Update Secrets

**Solusi:** Contact saya dengan:
1. Screenshot GitHub Secrets (blur sensitive data)
2. Screenshot error di GitHub Actions
3. Hasil test SSH connection manual

---

## 📞 QUICK LINKS

- **GitHub Secrets:** https://github.com/Dialius/JastipHype/settings/secrets/actions
- **GitHub Actions:** https://github.com/Dialius/JastipHype/actions
- **Hostinger hPanel:** https://hpanel.hostinger.com

---

**Priority:** HIGH  
**Time:** 10-15 menit  
**Difficulty:** MEDIUM

---

**Created:** 13 Februari 2026  
**Last Updated:** After SSH credentials verification
