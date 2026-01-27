$ErrorActionPreference = "Stop"

Write-Host "Downloading BCA..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg' -OutFile 'public/images/payment/banks/bca.svg'

Write-Host "Downloading Mandiri..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg' -OutFile 'public/images/payment/banks/mandiri.svg'

Write-Host "Downloading BNI..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg' -OutFile 'public/images/payment/banks/bni.svg'

Write-Host "Downloading BRI..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg' -OutFile 'public/images/payment/banks/bri.svg'

Write-Host "Downloading GoPay..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg' -OutFile 'public/images/payment/ewallet/gopay.svg'

Write-Host "Downloading Dana..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg' -OutFile 'public/images/payment/ewallet/dana.svg'

Write-Host "Downloading OVO..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg' -OutFile 'public/images/payment/ewallet/ovo.svg'

Write-Host "Downloading ShopeePay..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/f/fe/Shopee.svg' -OutFile 'public/images/payment/ewallet/shopeepay.svg'

Write-Host "Downloading QRIS..."
Invoke-WebRequest -Uri 'https://upload.wikimedia.org/wikipedia/commons/1/15/QRIS_General_Logo.svg' -OutFile 'public/images/payment/ewallet/qris.svg'

Write-Host "Downloads complete."
