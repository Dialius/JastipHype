# ✅ AUTO-DEPLOYMENT CHECKLIST

Print atau bookmark halaman ini untuk reference cepat!

---

## 🔧 SETUP (Sekali Saja)

### Di Windows (Local Computer):

- [ ] Buka PowerShell di folder project
- [ ] Run: `powershell -ExecutionPolicy Bypass -File generate-ssh-key-for-github-actions.ps1`
- [ ] Copy PUBLIC key (yang ada .pub)
- [ ] Copy PRIVATE key (yang tanpa .pub)
- [ ] Simpan kedua key di tempat aman

### Di Hostinger Server:

- [ ] SSH login: `ssh u909490256@jastiphype.shop -p 65002`
- [ ] Navigate: `cd /home/u909490256/domains/jastiphype.shop`
- [ ] Run: `bash setup-github-actions-ssh.sh`
- [ ] Paste PUBLIC key saat diminta
- [ ] Verify: Script menampilkan "✅ SETUP COMPLETE!"
- [ ] Logout: `exit`

### Di GitHub:

- [ ] Buka: https://github.com/Dialius/JastipHype/settings/secrets/actions
- [ ] Update/Create secret: `SSH_PRIVATE_KEY`
- [ ] Paste PRIVATE key (yang tanpa .pub)
- [ ] Save
- [ ] Verify secrets lain ada:
  - [ ] `SSH_HOST` = `jastiphype.shop`
  - [ ] `SSH_USERNAME` = `u909490256`
  - [ ] `SSH_PORT` = `65002`

### Test:

- [ ] Test SSH dari local: `ssh -i github-actions-key -p 65002 u909490256@jastiphype.shop`
- [ ] Berhasil login? ✅
- [ ] Buka: https://github.com/Dialius/JastipHype/actions
- [ ] Klik "Deploy to Hostinger"
- [ ] Klik "Run workflow"
- [ ] Monitor progress
- [ ] Deployment berhasil? ✅
- [ ] Website accessible: https://jastiphype.shop ✅

---

## 🔄 DAILY WORKFLOW

Setelah setup, workflow harian:

- [ ] Edit code di local
- [ ] Test di local (optional): `php artisan serve`
- [ ] Commit: `git add . && git commit -m "Your message"`
- [ ] Push: `git push origin master`
- [ ] Monitor GitHub Actions: https://github.com/Dialius/JastipHype/actions
- [ ] Deployment selesai? ✅
- [ ] Test website: https://jastiphype.shop ✅

**Total waktu: 2-5 menit otomatis!** 🎉

---

## 🆘 TROUBLESHOOTING

### ❌ Error: "Permission denied (publickey)"

- [ ] Re-run `setup-github-actions-ssh.sh` di server
- [ ] Verify public key di `~/.ssh/authorized_keys`
- [ ] Check permissions: `ls -la ~/.ssh/`
- [ ] `.ssh` harus 700, `authorized_keys` harus 600
- [ ] Fix: `chmod 700 ~/.ssh && chmod 600 ~/.ssh/authorized_keys`

### ❌ Website 404 setelah deploy

- [ ] SSH ke server
- [ ] Run: `cd /home/u909490256/domains/jastiphype.shop`
- [ ] Run: `bash fix-404-error.sh`
- [ ] Clear browser cache
- [ ] Test: https://jastiphype.shop

### ❌ Deployment timeout

- [ ] Check GitHub Actions logs
- [ ] Verify server tidak down
- [ ] Test SSH manual: `ssh -p 65002 u909490256@jastiphype.shop`
- [ ] Check Hostinger status

### ❌ Changes tidak muncul di website

- [ ] Verify deployment berhasil di GitHub Actions
- [ ] SSH ke server
- [ ] Check: `cd /home/u909490256/domains/jastiphype.shop && git log -1`
- [ ] Clear Laravel cache: `php artisan cache:clear`
- [ ] Clear browser cache

---

## 📋 VERIFICATION CHECKLIST

Setelah deployment, verify:

- [ ] Homepage load: https://jastiphype.shop
- [ ] Login page: https://jastiphype.shop/login
- [ ] Admin page: https://jastiphype.shop/admin/login
- [ ] Images load correctly
- [ ] No 404 errors
- [ ] No 500 errors
- [ ] CSS/JS load correctly

---

## 📞 QUICK COMMANDS

### SSH Login:
```bash
ssh u909490256@jastiphype.shop -p 65002
```

### Check Deployment Status:
```bash
cd /home/u909490256/domains/jastiphype.shop
git log -1
git status
```

### Fix 404:
```bash
cd /home/u909490256/domains/jastiphype.shop
bash fix-404-error.sh
```

### Clear Cache:
```bash
cd /home/u909490256/domains/jastiphype.shop
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Check Logs:
```bash
tail -50 /home/u909490256/domains/jastiphype.shop/storage/logs/laravel.log
```

---

## 🔗 QUICK LINKS

- **GitHub Actions**: https://github.com/Dialius/JastipHype/actions
- **GitHub Secrets**: https://github.com/Dialius/JastipHype/settings/secrets/actions
- **Website**: https://jastiphype.shop
- **Hostinger hPanel**: https://hpanel.hostinger.com

---

## 📚 DOCUMENTATION

- **Quick Start**: `QUICK_START_AUTO_DEPLOY.md`
- **Full Guide**: `FIX_GITHUB_ACTIONS_SSH.md`
- **404 Fix**: `FIX_404_ERROR.md`
- **Summary**: `AUTO_DEPLOY_SUMMARY.md`

---

**Last Updated**: 2026-02-12  
**Status**: ✅ Ready to Use

---

**Print this page for quick reference!** 📄
