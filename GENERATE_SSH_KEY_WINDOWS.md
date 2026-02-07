# Cara Generate SSH Key di Windows

## Metode 1: Menggunakan PowerShell (Built-in Windows)

### Langkah 1: Buka PowerShell

1. Tekan **Windows + X**
2. Pilih **"Windows PowerShell"** atau **"Terminal"**

### Langkah 2: Generate SSH Key

Copy-paste command ini:

```powershell
ssh-keygen -t ed25519 -C "hostinger-jastiphype" -f "$env:USERPROFILE\.ssh\hostinger_deploy"
```

**Penjelasan**:
- `-t ed25519`: Tipe key (modern dan aman)
- `-C "hostinger-jastiphype"`: Comment/label untuk key
- `-f`: Lokasi file key

### Langkah 3: Tekan Enter 3x

Akan muncul pertanyaan:
1. **"Enter passphrase"** → Tekan **Enter** (kosongkan)
2. **"Enter same passphrase again"** → Tekan **Enter** lagi

**Output**:
```
Your identification has been saved in C:\Users\YourName\.ssh\hostinger_deploy
Your public key has been saved in C:\Users\YourName\.ssh\hostinger_deploy.pub
The key fingerprint is:
SHA256:abc123... hostinger-jastiphype
```

### Langkah 4: Copy Public Key

```powershell
Get-Content "$env:USERPROFILE\.ssh\hostinger_deploy.pub"
```

**Copy semua text** yang muncul (dimulai dengan `ssh-ed25519 AAAA...`)

### Langkah 5: Add ke GitHub

1. Buka GitHub → Repository **JastiHype**
2. **Settings** → **Deploy keys** → **Add deploy key**
3. **Title**: `Hostinger Deploy Key`
4. **Key**: Paste public key dari langkah 4
5. **Allow write access**: ❌ Jangan centang
6. Klik **Add key**

### Langkah 6: Upload Private Key ke Hostinger

**Via SSH**:

```powershell
# Login SSH ke Hostinger
ssh -p 65002 u909490256@195.35.62.164

# Buat folder .ssh jika belum ada
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Exit dari SSH
exit
```

**Upload private key**:

```powershell
# Copy private key ke Hostinger
scp -P 65002 "$env:USERPROFILE\.ssh\hostinger_deploy" u909490256@195.35.62.164:~/.ssh/
```

**Kembali login SSH dan setup**:

```bash
ssh -p 65002 u909490256@195.35.62.164

# Set permission
chmod 600 ~/.ssh/hostinger_deploy

# Add ke SSH agent
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/hostinger_deploy

# Configure SSH untuk GitHub
cat >> ~/.ssh/config << 'EOF'
Host github.com
  HostName github.com
  User git
  IdentityFile ~/.ssh/hostinger_deploy
  IdentitiesOnly yes
EOF

chmod 600 ~/.ssh/config

# Test koneksi
ssh -T git@github.com
```

Jika berhasil, akan muncul:
```
Hi username/JastiHype! You've successfully authenticated...
```

### Langkah 7: Clone Repository

```bash
cd /home/u909490256/domains/jastiphype.shop

# Clone repository
git clone git@github.com:username/JastiHype.git .
```

Ganti `username` dengan username GitHub Anda.

---

## Metode 2: Menggunakan Git Bash (Jika Sudah Install Git)

### Langkah 1: Buka Git Bash

1. Klik kanan di desktop
2. Pilih **"Git Bash Here"**

### Langkah 2: Generate Key

```bash
ssh-keygen -t ed25519 -C "hostinger-jastiphype" -f ~/.ssh/hostinger_deploy
```

Tekan **Enter** 3x (kosongkan passphrase)

### Langkah 3: Copy Public Key

```bash
cat ~/.ssh/hostinger_deploy.pub
```

Copy semua text yang muncul.

### Langkah 4-7: Sama seperti Metode 1

---

## Metode 3: Menggunakan PuTTYgen (GUI)

### Langkah 1: Download PuTTYgen

Download dari: https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html

### Langkah 2: Generate Key

1. Buka **PuTTYgen**
2. Pilih **"EdDSA"** atau **"RSA"** (2048 bits)
3. Klik **"Generate"**
4. Gerakkan mouse untuk generate randomness
5. **Key comment**: `hostinger-jastiphype`
6. **Key passphrase**: Kosongkan

### Langkah 3: Save Keys

1. **Save public key**: `hostinger_deploy.pub`
2. **Save private key**: `hostinger_deploy.ppk`

### Langkah 4: Copy Public Key

Copy text dari box **"Public key for pasting into OpenSSH authorized_keys file"**

### Langkah 5: Convert ke OpenSSH Format (Untuk Server)

1. Menu **Conversions** → **Export OpenSSH key**
2. Save sebagai: `hostinger_deploy` (tanpa extension)

### Langkah 6: Add ke GitHub

Sama seperti Metode 1, Langkah 5.

### Langkah 7: Upload ke Hostinger

Gunakan FileZilla atau WinSCP:
1. Upload file `hostinger_deploy` ke `/home/u909490256/.ssh/`
2. Set permission: 600

---

## Troubleshooting

### Error: "ssh-keygen not found"

**Solusi**: Install Git for Windows
- Download: https://git-scm.com/download/win
- Install dengan default settings
- Restart PowerShell

### Error: "Permission denied (publickey)"

**Penyebab**: Key belum ter-setup dengan benar

**Solusi**:
1. Pastikan public key sudah di-add ke GitHub Deploy Keys
2. Pastikan private key ada di server: `~/.ssh/hostinger_deploy`
3. Pastikan permission benar: `chmod 600 ~/.ssh/hostinger_deploy`
4. Test koneksi: `ssh -T git@github.com`

### Error: "Could not open a connection to your authentication agent"

**Solusi**:
```bash
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/hostinger_deploy
```

### Tidak bisa upload via SCP

**Alternatif**: Upload via FileZilla/WinSCP
1. Connect ke Hostinger via FTP/SFTP
2. Navigate ke `/home/u909490256/.ssh/`
3. Upload file `hostinger_deploy`
4. Via SSH, set permission: `chmod 600 ~/.ssh/hostinger_deploy`

---

## Verifikasi Setup Berhasil

Setelah semua setup, test:

```bash
# Login SSH
ssh -p 65002 u909490256@195.35.62.164

# Test GitHub connection
ssh -T git@github.com

# Jika berhasil, clone repo
cd /home/u909490256/domains/jastiphype.shop
git clone git@github.com:username/JastiHype.git .
```

Jika semua berhasil, Anda bisa:
- ✅ Clone private repository
- ✅ Pull updates
- ✅ Setup GitHub Actions auto-deploy

---

## Catatan Keamanan

⚠️ **PENTING**:
- **JANGAN share private key** dengan siapa pun
- **JANGAN commit private key** ke Git
- **JANGAN upload private key** ke GitHub
- Private key hanya untuk server Hostinger
- Public key untuk GitHub Deploy Keys

---

## Lokasi File

Setelah generate, file akan ada di:

**Windows PowerShell**:
- Private key: `C:\Users\YourName\.ssh\hostinger_deploy`
- Public key: `C:\Users\YourName\.ssh\hostinger_deploy.pub`

**Git Bash**:
- Private key: `~/.ssh/hostinger_deploy`
- Public key: `~/.ssh/hostinger_deploy.pub`

**PuTTYgen**:
- Private key: `hostinger_deploy.ppk` (PuTTY format)
- Private key OpenSSH: `hostinger_deploy` (untuk server)
- Public key: `hostinger_deploy.pub`

---

## Next Steps

Setelah SSH key ter-setup:

1. ✅ Clone repository di Hostinger
2. ✅ Setup GitHub Actions (opsional)
3. ✅ Deploy Laravel app
4. ✅ Test website

Mau lanjut ke langkah mana?
