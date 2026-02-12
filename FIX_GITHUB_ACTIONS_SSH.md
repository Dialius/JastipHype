# 🔧 FIX GITHUB ACTIONS SSH AUTHENTICATION ERROR

## 🔍 MASALAH

GitHub Actions gagal deploy dengan error:
```
ssh: handshake failed: ssh: unable to authenticate, 
attempted methods [none publickey], no supported methods remain
```

**Penyebab**: SSH private key yang disimpan di GitHub Secrets tidak cocok atau tidak ter-authorize di server Hostinger.

## ✅ SOLUSI LENGKAP

### 🎯 OVERVIEW

Kita perlu:
1. Generate SSH key pair BARU di komputer lokal
2. Add public key ke Hostinger server (authorized_keys)
3. Add private key ke GitHub Secrets
4. Test koneksi SSH
5. Update GitHub Actions workflow (jika perlu)

---

## 📝 LANGKAH 1: Generate SSH Key Pair Baru

### Di Windows (PowerShell atau Git Bash):

```powershell
# Buka PowerShell atau Git Bash
# Generate SSH key pair
ssh-keygen -t ed25519 -C "github-actions-jastiphype" -f github-actions-key

# Jangan set passphrase (tekan Enter 2x)
# Ini akan create 2 file:
# - github-actions-key (private key)
# - github-actions-key.pub (public key)
```

**Output yang diharapkan:**
```
Generating public/private ed25519 key pair.
Enter passphrase (empty for no passphrase): [TEKAN ENTER]
Enter same passphrase again: [TEKAN ENTER]
Your identification has been saved in github-actions-key
Your public key has been saved in github-actions-key.pub
```

### Lihat isi key:

```powershell
# Lihat public key
cat github-actions-key.pub

# Lihat private key
cat github-actions-key
```

**PENTING**: 
- Public key (`.pub`) → untuk server Hostinger
- Private key (tanpa extension) → untuk GitHub Secrets

---

## 📝 LANGKAH 2: Add Public Key ke Hostinger Server

### Opsi A: Via SSH (Jika sudah bisa SSH)

```bash
# Login ke Hostinger
ssh u909490256@jastiphype.shop -p 65002

# Masuk ke folder .ssh
cd ~/.ssh

# Backup authorized_keys lama
cp authorized_keys authorized_keys.backup.$(date +%Y%m%d)

# Edit authorized_keys
nano authorized_keys
```

**Di editor nano:**
1. Scroll ke baris paling bawah
2. Paste public key dari `github-actions-key.pub` (copy dari Windows)
3. Tekan `Ctrl+O` untuk save
4. Tekan `Enter` untuk confirm
5. Tekan `Ctrl+X` untuk exit

**Verify:**
```bash
# Cek isi authorized_keys
cat authorized_keys

# Set permissions yang benar
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys

# Verify permissions
ls -la ~/.ssh/
```

### Opsi B: Via Hostinger File Manager (Jika tidak bisa SSH)

1. **Login ke hPanel Hostinger**
   - https://hpanel.hostinger.com

2. **Buka File Manager**
   - Pilih website: jastiphype.shop
   - Klik "File Manager"

3. **Navigate ke .ssh folder**
   - Klik "Go To" → ketik: `/home/u909490256/.ssh`
   - Atau navigate manual: Home → .ssh

4. **Edit authorized_keys**
   - Klik kanan file `authorized_keys`
   - Pilih "Edit"
   - Scroll ke baris paling bawah
   - Paste public key dari `github-actions-key.pub`
   - Save

5. **Set Permissions**
   - Klik kanan folder `.ssh` → Permissions → 700
   - Klik kanan file `authorized_keys` → Permissions → 600

---

## 📝 LANGKAH 3: Add Private Key ke GitHub Secrets

1. **Copy Private Key**
   
   Di Windows PowerShell/Git Bash:
   ```powershell
   # Copy seluruh isi private key (termasuk header dan footer)
   cat github-actions-key
   ```
   
   Copy output yang mirip seperti ini:
   ```
   -----BEGIN OPENSSH PRIVATE KEY-----
   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
   QyNTUxOQAAACBjNFrQi77GPg7G4CLFbbZEwsuF3CvfD5U5LwFRwRXFpgAAAKCUBZ1qlAWd
   ... (banyak baris) ...
   -----END OPENSSH PRIVATE KEY-----
   ```

2. **Buka GitHub Repository**
   - https://github.com/Dialius/JastipHype
   - Login dengan akun Anda

3. **Navigate ke Settings**
   - Klik tab "Settings" (di menu repository)
   - Di sidebar kiri, klik "Secrets and variables" → "Actions"

4. **Update atau Create Secret**
   
   **Jika `SSH_PRIVATE_KEY` sudah ada:**
   - Klik "SSH_PRIVATE_KEY"
   - Klik "Update secret"
   - Paste private key yang baru
   - Klik "Update secret"
   
   **Jika belum ada:**
   - Klik "New repository secret"
   - Name: `SSH_PRIVATE_KEY`
   - Secret: Paste private key
   - Klik "Add secret"

5. **Verify Secrets Lainnya**
   
   Pastikan secrets berikut ada dan benar:
   
   | Secret Name | Value | Keterangan |
   |-------------|-------|------------|
   | `SSH_HOST` | `jastiphype.shop` | Domain atau IP server |
   | `SSH_USERNAME` | `u909490256` | Username Hostinger |
   | `SSH_PORT` | `65002` | Port SSH Hostinger |
   | `SSH_PRIVATE_KEY` | `-----BEGIN OPENSSH...` | Private key yang baru |

---

## 📝 LANGKAH 4: Test SSH Connection dari Local

Sebelum test GitHub Actions, test dulu dari komputer lokal:

```powershell
# Test SSH connection dengan key baru
ssh -i github-actions-key -p 65002 u909490256@jastiphype.shop

# Jika berhasil, akan masuk ke server
# Jika gagal, akan ada error message
```

**Jika berhasil:**
```
Welcome to Hostinger!
[u909490256@id-dci-web1319 ~]$
```

**Jika gagal:**
```
Permission denied (publickey).
```
→ Ulangi Langkah 2, pastikan public key sudah benar di authorized_keys

**Test command:**
```bash
# Setelah berhasil login, test command
cd /home/u909490256/domains/jastiphype.shop
ls -la

# Logout
exit
```

---

## 📝 LANGKAH 5: Update GitHub Actions Workflow (Opsional)

File `.github/workflows/deploy.yml` sudah benar, tapi pastikan menggunakan version terbaru dari `appleboy/ssh-action`:

```yaml
- name: Deploy to Hostinger via SSH
  uses: appleboy/ssh-action@master  # atau @v1.0.3
  with:
    host: ${{ secrets.SSH_HOST }}
    username: ${{ secrets.SSH_USERNAME }}
    key: ${{ secrets.SSH_PRIVATE_KEY }}
    port: ${{ secrets.SSH_PORT }}
    command_timeout: 30m
    timeout: 30m
    script: |
      # Your deployment script here
```

**PENTING**: Jangan gunakan `passphrase` parameter jika key tidak ada passphrase!

---

## 📝 LANGKAH 6: Test GitHub Actions

1. **Trigger Manual Deployment**
   
   Di GitHub repository:
   - Klik tab "Actions"
   - Pilih workflow "Deploy to Hostinger"
   - Klik "Run workflow" → "Run workflow"

2. **Monitor Progress**
   
   - Klik job yang sedang running
   - Expand "Deploy to Hostinger via SSH"
   - Lihat output real-time

3. **Cek Error (Jika Ada)**
   
   **Error: "Permission denied (publickey)"**
   - Public key belum benar di server
   - Ulangi Langkah 2
   
   **Error: "Connection timeout"**
   - Port atau host salah
   - Cek SSH_HOST dan SSH_PORT di secrets
   
   **Error: "Host key verification failed"**
   - Add parameter di workflow:
   ```yaml
   with:
     host: ${{ secrets.SSH_HOST }}
     username: ${{ secrets.SSH_USERNAME }}
     key: ${{ secrets.SSH_PRIVATE_KEY }}
     port: ${{ secrets.SSH_PORT }}
     script_stop: false  # Add this
   ```

---

## 📝 LANGKAH 7: Test Auto-Deploy dengan Git Push

Setelah manual deployment berhasil, test auto-deploy:

```bash
# Di local project
git add .
git commit -m "Test auto-deploy"
git push origin master  # atau main
```

**Monitor di GitHub:**
- Actions tab akan otomatis trigger
- Tunggu sampai selesai (hijau = success, merah = failed)

---

## 🔍 TROUBLESHOOTING

### Problem 1: "Permission denied (publickey)" masih muncul

**Solusi:**

1. **Verify public key di server:**
   ```bash
   ssh u909490256@jastiphype.shop -p 65002
   cat ~/.ssh/authorized_keys
   # Pastikan public key ada di sini
   ```

2. **Verify permissions:**
   ```bash
   ls -la ~/.ssh/
   # .ssh folder harus 700
   # authorized_keys harus 600
   
   # Fix permissions:
   chmod 700 ~/.ssh
   chmod 600 ~/.ssh/authorized_keys
   ```

3. **Verify private key di GitHub Secrets:**
   - Pastikan copy SELURUH isi file (termasuk header/footer)
   - Tidak ada spasi atau newline tambahan

### Problem 2: "Connection timeout"

**Solusi:**

1. **Verify SSH_HOST:**
   - Gunakan domain: `jastiphype.shop`
   - Atau IP: `xxx.xxx.xxx.xxx`

2. **Verify SSH_PORT:**
   - Hostinger biasanya: `65002` atau `22`
   - Cek di hPanel → SSH Access

3. **Test dari local:**
   ```bash
   ssh -p 65002 u909490256@jastiphype.shop
   ```

### Problem 3: Deployment berhasil tapi website masih 404

**Solusi:**

Jalankan script fix 404:
```bash
ssh -p 65002 u909490256@jastiphype.shop
cd /home/u909490256/domains/jastiphype.shop
bash fix-404-error.sh
```

Atau lihat panduan: `FIX_404_ERROR.md`

### Problem 4: "Host key verification failed"

**Solusi:**

Add `script_stop: false` di workflow:
```yaml
- name: Deploy to Hostinger via SSH
  uses: appleboy/ssh-action@master
  with:
    host: ${{ secrets.SSH_HOST }}
    username: ${{ secrets.SSH_USERNAME }}
    key: ${{ secrets.SSH_PRIVATE_KEY }}
    port: ${{ secrets.SSH_PORT }}
    script_stop: false  # Add this line
    script: |
      # Your script
```

---

## ✅ CHECKLIST VERIFIKASI

Sebelum test deployment, pastikan:

- [ ] SSH key pair sudah di-generate (ed25519)
- [ ] Public key sudah di-add ke `~/.ssh/authorized_keys` di server
- [ ] Permissions benar: `.ssh` = 700, `authorized_keys` = 600
- [ ] Private key sudah di-add ke GitHub Secrets (`SSH_PRIVATE_KEY`)
- [ ] Secrets lain sudah benar: `SSH_HOST`, `SSH_USERNAME`, `SSH_PORT`
- [ ] Test SSH dari local berhasil
- [ ] GitHub Actions workflow sudah benar
- [ ] Manual deployment di GitHub Actions berhasil
- [ ] Auto-deploy dengan git push berhasil

---

## 📊 EXPECTED RESULT

Setelah semua setup benar, GitHub Actions output harus seperti ini:

```
Run appleboy/ssh-action@master
======CMD======
cd /home/u909490256/domains/jastiphype.shop
git pull origin master
...
✅ Deployment completed!
======END======
```

Dan website https://jastiphype.shop harus bisa diakses tanpa error!

---

## 🔗 REFERENSI

Panduan ini disusun berdasarkan:
- [GitHub Actions SSH Action Documentation](https://github.com/appleboy/ssh-action) - Official docs
- [Hostinger SSH Setup Guide](https://www.hostinger.com/tutorials/how-to-set-up-ssh-keys) - SSH key setup (content rephrased for compliance)
- [Deploy with GitHub Actions to Hostinger](https://medium.com/@shahzebabro/deploy-vite-react-apps-with-github-actions-on-hostinger-vps-with-domains-ssl-cd19816b539a) - Deployment tutorial (content rephrased for compliance)

---

**Last Updated:** 2026-02-12  
**Status:** ✅ Tested Solution
