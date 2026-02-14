# Deployment Testing Script
Write-Host "Testing Deployment..." -ForegroundColor Cyan

$testsPassed = 0
$testsFailed = 0

# Test 1: Site accessibility
Write-Host "1. Testing site accessibility..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop" -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "   PASS: Site is accessible" -ForegroundColor Green
        $testsPassed++
    }
} catch {
    Write-Host "   FAIL: Site not accessible" -ForegroundColor Red
    $testsFailed++
}

# Test 2: Privacy policy
Write-Host "2. Testing privacy policy..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "https://jastiphype.shop/gdpr/privacy-policy" -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "   PASS: Privacy policy accessible" -ForegroundColor Green
        $testsPassed++
    }
} catch {
    Write-Host "   FAIL: Privacy policy not accessible" -ForegroundColor Red
    $testsFailed++
}

# Test 3: Server sync
Write-Host "3. Checking server sync..." -ForegroundColor Yellow
$serverCommit = ssh -p 65002 u909490256@153.92.9.187 "cd domains/jastiphype.shop && git log -1 --oneline"
$localCommit = git log -1 --oneline
Write-Host "   Server: $serverCommit" -ForegroundColor Cyan
Write-Host "   Local:  $localCommit" -ForegroundColor Cyan
if ($serverCommit -eq $localCommit) {
    Write-Host "   PASS: Server synchronized" -ForegroundColor Green
    $testsPassed++
} else {
    Write-Host "   WARN: Server not synchronized" -ForegroundColor Yellow
}

# Summary
Write-Host "`nTest Summary:" -ForegroundColor Cyan
Write-Host "Passed: $testsPassed" -ForegroundColor Green
Write-Host "Failed: $testsFailed" -ForegroundColor Red

if ($testsFailed -eq 0) {
    Write-Host "`nAll tests passed!" -ForegroundColor Green
} else {
    Write-Host "`nSome tests failed!" -ForegroundColor Yellow
}
