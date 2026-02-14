# Test Deployment Status
# This script tests the deployment and GDPR dashboard

$ErrorActionPreference = "Continue"

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  DEPLOYMENT STATUS CHECK" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

# Test 1: Check if site is accessible
Write-Host "1. Testing main site..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop" -UseBasicParsing -TimeoutSec 10
    Write-Host "   ✅ Site is accessible (Status: $($response.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "   ❌ Site is NOT accessible" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 2: Check GDPR dashboard (requires login, so we expect redirect)
Write-Host "`n2. Testing GDPR dashboard..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop/gdpr/dashboard" -UseBasicParsing -TimeoutSec 10 -MaximumRedirection 0 -ErrorAction SilentlyContinue
    if ($response.StatusCode -eq 302 -or $response.StatusCode -eq 301) {
        Write-Host "   ✅ GDPR dashboard route exists (redirects to login)" -ForegroundColor Green
    } else {
        Write-Host "   ✅ GDPR dashboard accessible (Status: $($response.StatusCode))" -ForegroundColor Green
    }
} catch {
    if ($_.Exception.Response.StatusCode -eq 302 -or $_.Exception.Response.StatusCode -eq 301) {
        Write-Host "   ✅ GDPR dashboard route exists (redirects to login)" -ForegroundColor Green
    } else {
        Write-Host "   ❌ GDPR dashboard error" -ForegroundColor Red
        Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
    }
}

# Test 3: Check privacy policy (public page)
Write-Host "`n3. Testing privacy policy page..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop/gdpr/privacy-policy" -UseBasicParsing -TimeoutSec 10
    Write-Host "   ✅ Privacy policy accessible (Status: $($response.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "   ❌ Privacy policy error" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 4: Check cookie policy (public page)
Write-Host "`n4. Testing cookie policy page..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop/gdpr/cookie-policy" -UseBasicParsing -TimeoutSec 10
    Write-Host "   ✅ Cookie policy accessible (Status: $($response.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "   ❌ Cookie policy error" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 5: Check GitHub Actions latest run
Write-Host "`n5. Checking latest GitHub Actions run..." -ForegroundColor Yellow
Write-Host "   Visit: https://github.com/Dialius/JastipHype/actions" -ForegroundColor Cyan

# Test 6: Check local vs server commit
Write-Host "`n6. Checking commit sync..." -ForegroundColor Yellow
$localCommit = git log -1 --format="%h %s"
Write-Host "   Local:  $localCommit" -ForegroundColor Cyan
Write-Host "   Server: d613062c fix(deploy): improve error handling and logging" -ForegroundColor Cyan
if ($localCommit -match "d613062c") {
    Write-Host "   ✅ Commits are in sync" -ForegroundColor Green
} else {
    Write-Host "   ⚠️  Commits may be out of sync" -ForegroundColor Yellow
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  NEXT STEPS" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "If site shows 403 error:" -ForegroundColor Yellow
Write-Host "1. Connect via SSH:" -ForegroundColor White
Write-Host "   ssh u909490256@jastiphype.shop -p 65002" -ForegroundColor Green
Write-Host "`n2. Check public_html/index.php:" -ForegroundColor White
Write-Host "   cd /home/u909490256/public_html" -ForegroundColor Green
Write-Host "   ls -la" -ForegroundColor Green
Write-Host "`n3. If index.php is missing or wrong, run:" -ForegroundColor White
Write-Host "   cd /home/u909490256/domains/jastiphype.shop" -ForegroundColor Green
Write-Host "   bash fix-public-html-index.sh" -ForegroundColor Green
Write-Host "`n4. Check Laravel logs:" -ForegroundColor White
Write-Host "   tail -50 storage/logs/laravel.log" -ForegroundColor Green

Write-Host "`n========================================`n" -ForegroundColor Cyan
