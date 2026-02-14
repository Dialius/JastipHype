# Check Deployment Status Script
# This script checks if the deployment was successful

Write-Host "`n=== DEPLOYMENT STATUS CHECK ===" -ForegroundColor Cyan
Write-Host ""

# 1. Check local commit
Write-Host "1. Local Repository Status:" -ForegroundColor Yellow
$localCommit = git log -1 --oneline
Write-Host "   Latest commit: $localCommit" -ForegroundColor White
Write-Host ""

# 2. Check if push was successful
Write-Host "2. Remote Repository Status:" -ForegroundColor Yellow
$remoteCommit = git ls-remote origin master | Select-Object -First 1
Write-Host "   Remote HEAD: $($remoteCommit.Substring(0, 40))" -ForegroundColor White
Write-Host ""

# 3. Check GitHub Actions
Write-Host "3. GitHub Actions:" -ForegroundColor Yellow
Write-Host "   URL: https://github.com/Dialius/JastipHype/actions" -ForegroundColor Cyan
Write-Host "   Check the latest workflow run for deployment status" -ForegroundColor White
Write-Host ""

# 4. Check website
Write-Host "4. Website Health Check:" -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop" -Method Head -TimeoutSec 10 -UseBasicParsing
    if ($response.StatusCode -eq 200) {
        Write-Host "   ✅ Website is responding (HTTP $($response.StatusCode))" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️ Website returned HTTP $($response.StatusCode)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ❌ Website is not responding: $($_.Exception.Message)" -ForegroundColor Red
}
Write-Host ""

# 5. Check GDPR Dashboard
Write-Host "5. GDPR Dashboard Check:" -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop/gdpr/dashboard" -Method Head -TimeoutSec 10 -UseBasicParsing
    if ($response.StatusCode -eq 200) {
        Write-Host "   ✅ GDPR Dashboard is accessible (HTTP $($response.StatusCode))" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️ GDPR Dashboard returned HTTP $($response.StatusCode)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ⚠️ GDPR Dashboard check: $($_.Exception.Message)" -ForegroundColor Yellow
    Write-Host "   (This is normal if you're not logged in)" -ForegroundColor Gray
}
Write-Host ""

# 6. Summary
Write-Host "=== SUMMARY ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "1. Visit GitHub Actions to see deployment logs:" -ForegroundColor White
Write-Host "   https://github.com/Dialius/JastipHype/actions" -ForegroundColor Cyan
Write-Host ""
Write-Host "2. If deployment succeeds, test the GDPR dashboard:" -ForegroundColor White
Write-Host "   https://jastiphype.shop/gdpr/dashboard" -ForegroundColor Cyan
Write-Host ""
Write-Host "3. Check for any errors in the deployment logs" -ForegroundColor White
Write-Host ""
