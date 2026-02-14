# Deployment Testing Guide

## 🧪 Pre-Deployment Testing

Before pushing to master, test these locally:

### 1. Code Quality
```bash
# Run PHP syntax check
php -l app/**/*.php

# Run Composer validation
composer validate

# Check for security vulnerabilities
composer audit
```

### 2. Build Assets
```bash
# Build production assets
npm run build

# Verify build output
ls -lh public/build/assets/
```

### 3. Database Migrations
```bash
# Test migrations on local database
php artisan migrate:fresh --seed

# Rollback test
php artisan migrate:rollback
php artisan migrate
```

### 4. Laravel Tests (if available)
```bash
# Run PHPUnit tests
php artisan test

# Or
./vendor/bin/phpunit
```

## 🚀 Post-Deployment Testing

After deployment completes, verify these:

### 1. Site Accessibility
```bash
# Check if site is up
curl -I https://jastiphype.shop

# Check response time
curl -w "@curl-format.txt" -o /dev/null -s https://jastiphype.shop
```

Create `curl-format.txt`:
```
time_namelookup:  %{time_namelookup}\n
time_connect:  %{time_connect}\n
time_starttransfer:  %{time_starttransfer}\n
time_total:  %{time_total}\n
```

### 2. Visual Testing Checklist

Visit these pages and verify they load correctly:

- [ ] Homepage: https://jastiphype.shop
- [ ] Product listing
- [ ] Product detail page
- [ ] Cart functionality
- [ ] Checkout process
- [ ] Admin login: https://jastiphype.shop/admin
- [ ] Admin dashboard
- [ ] Privacy Policy: https://jastiphype.shop/gdpr/privacy-policy
- [ ] Cookie Policy: https://jastiphype.shop/gdpr/cookie-policy

### 3. Asset Loading Check

Open browser DevTools (F12) and check:
- [ ] No 404 errors for CSS files
- [ ] No 404 errors for JS files
- [ ] No 404 errors for images
- [ ] No console errors
- [ ] Styles are applied correctly
- [ ] JavaScript is working

### 4. Database Check
```bash
# Connect to server
ssh -p 65002 u909490256@153.92.9.187

# Check migrations
cd domains/jastiphype.shop
php artisan migrate:status

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

### 5. Cache Verification
```bash
# Verify caches are working
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop

# Check config cache
php artisan config:cache
php artisan config:clear

# Check route cache
php artisan route:cache
php artisan route:clear

# Check view cache
php artisan view:cache
php artisan view:clear
```

### 6. Error Log Check
```bash
# Check for new errors
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop
tail -50 storage/logs/laravel.log
```

## 🔍 Automated Testing Script

Create `test-deployment.ps1`:
```powershell
# Deployment Testing Script
Write-Host "🧪 Testing Deployment..." -ForegroundColor Cyan

# Test 1: Site is accessible
Write-Host "`n1. Testing site accessibility..." -ForegroundColor Yellow
$response = Invoke-WebRequest -Uri "https://jastiphype.shop" -UseBasicParsing
if ($response.StatusCode -eq 200) {
    Write-Host "✅ Site is accessible" -ForegroundColor Green
} else {
    Write-Host "❌ Site returned status: $($response.StatusCode)" -ForegroundColor Red
}

# Test 2: Assets are loading
Write-Host "`n2. Testing asset loading..." -ForegroundColor Yellow
$cssTest = Invoke-WebRequest -Uri "https://jastiphype.shop" -UseBasicParsing
if ($cssTest.Content -match "build/assets/app-.*\.css") {
    Write-Host "✅ CSS assets found" -ForegroundColor Green
} else {
    Write-Host "❌ CSS assets not found" -ForegroundColor Red
}

# Test 3: Privacy policy page
Write-Host "`n3. Testing GDPR pages..." -ForegroundColor Yellow
$privacyTest = Invoke-WebRequest -Uri "https://jastiphype.shop/gdpr/privacy-policy" -UseBasicParsing
if ($privacyTest.StatusCode -eq 200) {
    Write-Host "✅ Privacy policy accessible" -ForegroundColor Green
} else {
    Write-Host "❌ Privacy policy failed" -ForegroundColor Red
}

# Test 4: Check server commit
Write-Host "`n4. Checking server commit..." -ForegroundColor Yellow
$serverCommit = ssh -p 65002 u909490256@153.92.9.187 "cd domains/jastiphype.shop && git log -1 --oneline"
Write-Host "Server commit: $serverCommit" -ForegroundColor Cyan

# Test 5: Check local commit
$localCommit = git log -1 --oneline
Write-Host "Local commit:  $localCommit" -ForegroundColor Cyan

if ($serverCommit -eq $localCommit) {
    Write-Host "✅ Server is synchronized" -ForegroundColor Green
} else {
    Write-Host "⚠️ Server is not synchronized" -ForegroundColor Yellow
}

Write-Host "`n🎉 Testing complete!" -ForegroundColor Cyan
```

Create `test-deployment.sh`:
```bash
#!/bin/bash
# Deployment Testing Script

echo "🧪 Testing Deployment..."

# Test 1: Site is accessible
echo -e "\n1. Testing site accessibility..."
if curl -f -s -o /dev/null https://jastiphype.shop; then
    echo "✅ Site is accessible"
else
    echo "❌ Site is not accessible"
fi

# Test 2: Check response time
echo -e "\n2. Testing response time..."
time=$(curl -o /dev/null -s -w '%{time_total}\n' https://jastiphype.shop)
echo "Response time: ${time}s"

# Test 3: Privacy policy page
echo -e "\n3. Testing GDPR pages..."
if curl -f -s -o /dev/null https://jastiphype.shop/gdpr/privacy-policy; then
    echo "✅ Privacy policy accessible"
else
    echo "❌ Privacy policy failed"
fi

# Test 4: Check server commit
echo -e "\n4. Checking server commit..."
server_commit=$(ssh -p 65002 u909490256@153.92.9.187 "cd domains/jastiphype.shop && git log -1 --oneline")
echo "Server commit: $server_commit"

# Test 5: Check local commit
local_commit=$(git log -1 --oneline)
echo "Local commit:  $local_commit"

if [ "$server_commit" = "$local_commit" ]; then
    echo "✅ Server is synchronized"
else
    echo "⚠️ Server is not synchronized"
fi

echo -e "\n🎉 Testing complete!"
```

## 📊 Testing Checklist

### Before Every Deployment
- [ ] Code compiles without errors
- [ ] Assets build successfully
- [ ] No syntax errors
- [ ] Migrations tested locally
- [ ] .env variables are correct

### After Every Deployment
- [ ] Site is accessible
- [ ] No 500 errors
- [ ] Assets are loading
- [ ] Styles are applied
- [ ] JavaScript works
- [ ] Database is accessible
- [ ] Migrations ran successfully
- [ ] No errors in logs
- [ ] Server commit matches local

### Weekly Testing
- [ ] Full site walkthrough
- [ ] Test all major features
- [ ] Check performance
- [ ] Review error logs
- [ ] Check disk space
- [ ] Verify backups

## 🚨 Common Issues

### Issue 1: Assets Not Loading
**Symptoms**: Broken styles, missing JavaScript
**Solution**:
```bash
npm run build
git add public/build -f
git commit -m "fix: rebuild assets"
git push
```

### Issue 2: 500 Error
**Symptoms**: White screen, server error
**Solution**:
```bash
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop
tail -50 storage/logs/laravel.log
php artisan optimize:clear
```

### Issue 3: Migration Failed
**Symptoms**: Database errors
**Solution**:
```bash
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop
php artisan migrate:status
php artisan migrate --force
```

### Issue 4: Cache Issues
**Symptoms**: Old content showing
**Solution**:
```bash
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📈 Performance Benchmarks

Track these metrics after deployment:

| Metric | Target | Current |
|--------|--------|---------|
| Homepage load time | < 2s | - |
| Time to first byte | < 500ms | - |
| Asset load time | < 1s | - |
| Database query time | < 100ms | - |

## 🔗 Related Documents

- [DEPLOYMENT_ROLLBACK_GUIDE.md](DEPLOYMENT_ROLLBACK_GUIDE.md) - Rollback procedures
- [AUTO_DEPLOY_SUCCESS.md](AUTO_DEPLOY_SUCCESS.md) - Deployment guide
- [AUTO_DEPLOY_ANALYSIS.md](AUTO_DEPLOY_ANALYSIS.md) - System analysis

---

**Last Updated**: February 14, 2026
**Status**: Active
