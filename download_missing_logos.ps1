$UserAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"

Write-Host "Downloading BNI..."
try {
    Invoke-WebRequest -Uri "https://upload.wikimedia.org/wikipedia/commons/7/74/Bank_Negara_Indonesia_logo_(2004).svg" -OutFile "public/images/payment/banks/bni.svg" -UserAgent $UserAgent
    Write-Host "BNI Downloaded."
} catch {
    Write-Error "Failed to download BNI: $_"
}

Write-Host "Downloading QRIS..."
try {
    Invoke-WebRequest -Uri "https://upload.wikimedia.org/wikipedia/commons/e/e0/QRIS_Logo.svg" -OutFile "public/images/payment/ewallet/qris.svg" -UserAgent $UserAgent
    Write-Host "QRIS Downloaded."
} catch {
    Write-Error "Failed to download QRIS: $_"
}
