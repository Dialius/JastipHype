# Translation Verification Script for Windows
# Run this locally to check if website has been updated

Write-Host "========================================" -ForegroundColor Blue
Write-Host "   Translation Verification Script" -ForegroundColor Blue
Write-Host "========================================" -ForegroundColor Blue
Write-Host ""

# Test URLs
$urls = @(
    "https://jastiphype.shop/gdpr/privacy-policy",
    "https://jastiphype.shop/gdpr/cookie-policy"
)

Write-Host "Checking website pages for English translations..." -ForegroundColor Yellow
Write-Host ""

foreach ($url in $urls) {
    Write-Host "Testing: $url" -ForegroundColor Cyan
    
    try {
        $response = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 10
        
        # Check for English keywords
        $hasEnglish = $false
        $englishKeywords = @("Last updated", "Cookie Details", "Accept All", "Essential Cookies", "Privacy Policy")
        
        foreach ($keyword in $englishKeywords) {
            if ($response.Content -match [regex]::Escape($keyword)) {
                Write-Host "  [OK] Found: '$keyword'" -ForegroundColor Green
                $hasEnglish = $true
            }
        }
        
        # Check for Indonesian keywords (should NOT be found)
        $indonesianKeywords = @("Terakhir diperbarui", "Detail Cookies", "Terima Semua", "Cookies Penting")
        $hasIndonesian = $false
        
        foreach ($keyword in $indonesianKeywords) {
            if ($response.Content -match [regex]::Escape($keyword)) {
                Write-Host "  [WARN] Still found Indonesian: '$keyword'" -ForegroundColor Red
                $hasIndonesian = $true
            }
        }
        
        if ($hasEnglish -and -not $hasIndonesian) {
            Write-Host "  [SUCCESS] Translation deployed!" -ForegroundColor Green
        } elseif ($hasIndonesian) {
            Write-Host "  [PENDING] Translation not yet deployed" -ForegroundColor Yellow
        }
    }
    catch {
        Write-Host "  [ERROR] Cannot access page: $($_.Exception.Message)" -ForegroundColor Red
    }
    
    Write-Host ""
}

Write-Host "========================================" -ForegroundColor Blue
Write-Host "   Verification Complete!" -ForegroundColor Blue
Write-Host "========================================" -ForegroundColor Blue
Write-Host ""

Write-Host "If translations are not deployed yet:" -ForegroundColor Yellow
Write-Host "1. Connect via SSH: ssh u909490256@jastiphype.shop -p 65002" -ForegroundColor Cyan
Write-Host "2. Run: cd /home/u909490256/domains/jastiphype.shop" -ForegroundColor Cyan
Write-Host "3. Run: git pull origin master" -ForegroundColor Cyan
Write-Host "4. Run: php artisan cache:clear && php artisan view:clear" -ForegroundColor Cyan
Write-Host ""
