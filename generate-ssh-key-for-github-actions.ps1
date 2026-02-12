# GitHub Actions SSH Key Generator for Windows
# Run this in PowerShell

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   GENERATE SSH KEY FOR GITHUB ACTIONS - WINDOWS" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

# Check if ssh-keygen is available
Write-Host "Checking if ssh-keygen is available..." -ForegroundColor Yellow
$sshKeygenPath = Get-Command ssh-keygen -ErrorAction SilentlyContinue

if (-not $sshKeygenPath) {
    Write-Host "ERROR: ssh-keygen not found!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please install one of the following:" -ForegroundColor Yellow
    Write-Host "  1. Git for Windows: https://git-scm.com/download/win" -ForegroundColor Cyan
    Write-Host "  2. OpenSSH Client (Windows 10+): Settings -> Apps -> Optional Features -> OpenSSH Client" -ForegroundColor Cyan
    Write-Host ""
    exit 1
}

Write-Host "OK - ssh-keygen found: $($sshKeygenPath.Source)" -ForegroundColor Green
Write-Host ""

# Set key filename
$keyName = "github-actions-key"
$keyPath = Join-Path $PWD $keyName

# Check if key already exists
if (Test-Path $keyPath) {
    Write-Host "WARNING: Key file already exists: $keyPath" -ForegroundColor Yellow
    $overwrite = Read-Host "Do you want to overwrite it? (yes/no)"
    if ($overwrite -ne "yes") {
        Write-Host "Aborted. Using existing key." -ForegroundColor Red
        Write-Host ""
        
        # Display existing keys
        if (Test-Path "$keyPath.pub") {
            Write-Host "Existing PUBLIC key:" -ForegroundColor Cyan
            Write-Host "============================================================" -ForegroundColor Blue
            Get-Content "$keyPath.pub"
            Write-Host "============================================================" -ForegroundColor Blue
        }
        
        Write-Host ""
        Write-Host "Continue with existing key? (yes/no)" -ForegroundColor Yellow
        $continue = Read-Host
        if ($continue -ne "yes") {
            exit 0
        }
    } else {
        Remove-Item $keyPath -Force -ErrorAction SilentlyContinue
        Remove-Item "$keyPath.pub" -Force -ErrorAction SilentlyContinue
    }
}

# Generate SSH key
if (-not (Test-Path $keyPath)) {
    Write-Host "Generating SSH key pair..." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "IMPORTANT: When prompted for passphrase, just press ENTER (no passphrase)" -ForegroundColor Red
    Write-Host ""
    
    # Generate key
    & ssh-keygen -t ed25519 -C "github-actions-jastiphype" -f $keyPath
    
    if ($LASTEXITCODE -ne 0) {
        Write-Host ""
        Write-Host "ERROR: Failed to generate SSH key!" -ForegroundColor Red
        exit 1
    }
    
    Write-Host ""
    Write-Host "OK - SSH key pair generated successfully!" -ForegroundColor Green
    Write-Host ""
}

# Display keys
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "                    YOUR SSH KEYS" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

# Public Key
Write-Host "PUBLIC KEY (for Hostinger server):" -ForegroundColor Green
Write-Host "============================================================" -ForegroundColor Blue
$publicKey = Get-Content "$keyPath.pub"
Write-Host $publicKey -ForegroundColor White
Write-Host "============================================================" -ForegroundColor Blue
Write-Host ""

# Private Key
Write-Host "PRIVATE KEY (for GitHub Secrets):" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Blue
Get-Content $keyPath
Write-Host "============================================================" -ForegroundColor Blue
Write-Host ""

# Copy to clipboard (if available)
Write-Host "Copy keys to clipboard?" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Copy PUBLIC key (for Hostinger)" -ForegroundColor Cyan
Write-Host "2. Copy PRIVATE key (for GitHub)" -ForegroundColor Cyan
Write-Host "3. Skip" -ForegroundColor Gray
Write-Host ""
$choice = Read-Host "Enter choice (1/2/3)"

switch ($choice) {
    "1" {
        $publicKey | Set-Clipboard
        Write-Host "OK - PUBLIC key copied to clipboard!" -ForegroundColor Green
    }
    "2" {
        Get-Content $keyPath | Set-Clipboard
        Write-Host "OK - PRIVATE key copied to clipboard!" -ForegroundColor Green
    }
    default {
        Write-Host "Skipped" -ForegroundColor Gray
    }
}

Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "                  NEXT STEPS" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "STEP 1: Add PUBLIC key to Hostinger server" -ForegroundColor Yellow
Write-Host "------------------------------------------------------------" -ForegroundColor Gray
Write-Host ""
Write-Host "Option A - Via SSH:" -ForegroundColor Cyan
Write-Host "  1. Login to Hostinger:" -ForegroundColor White
Write-Host "     ssh u909490256@jastiphype.shop -p 65002" -ForegroundColor Green
Write-Host ""
Write-Host "  2. Run setup script:" -ForegroundColor White
Write-Host "     cd /home/u909490256/domains/jastiphype.shop" -ForegroundColor Green
Write-Host "     bash setup-github-actions-ssh.sh" -ForegroundColor Green
Write-Host ""
Write-Host "  3. Paste the PUBLIC key when prompted" -ForegroundColor White
Write-Host ""
Write-Host "Option B - Via File Manager:" -ForegroundColor Cyan
Write-Host "  1. Login to hPanel: https://hpanel.hostinger.com" -ForegroundColor White
Write-Host "  2. Open File Manager" -ForegroundColor White
Write-Host "  3. Navigate to: /home/u909490256/.ssh/" -ForegroundColor White
Write-Host "  4. Edit file: authorized_keys" -ForegroundColor White
Write-Host "  5. Add PUBLIC key at the end" -ForegroundColor White
Write-Host "  6. Save and set permissions: 600" -ForegroundColor White
Write-Host ""

Write-Host "STEP 2: Add PRIVATE key to GitHub Secrets" -ForegroundColor Yellow
Write-Host "------------------------------------------------------------" -ForegroundColor Gray
Write-Host ""
Write-Host "  1. Go to: https://github.com/Dialius/JastipHype/settings/secrets/actions" -ForegroundColor White
Write-Host ""
Write-Host "  2. Update or create secret:" -ForegroundColor White
Write-Host "     Name:  SSH_PRIVATE_KEY" -ForegroundColor Green
Write-Host "     Value: [Paste PRIVATE key from above]" -ForegroundColor Green
Write-Host ""
Write-Host "  3. Verify other secrets exist:" -ForegroundColor White
Write-Host "     - SSH_HOST = jastiphype.shop" -ForegroundColor Cyan
Write-Host "     - SSH_USERNAME = u909490256" -ForegroundColor Cyan
Write-Host "     - SSH_PORT = 65002" -ForegroundColor Cyan
Write-Host ""

Write-Host "STEP 3: Test SSH connection" -ForegroundColor Yellow
Write-Host "------------------------------------------------------------" -ForegroundColor Gray
Write-Host ""
Write-Host "  Run this command:" -ForegroundColor White
Write-Host "  ssh -i $keyName -p 65002 u909490256@jastiphype.shop" -ForegroundColor Green
Write-Host ""
Write-Host "  If successful, you'll see:" -ForegroundColor White
Write-Host "  Welcome to Hostinger!" -ForegroundColor Cyan
Write-Host ""

Write-Host "STEP 4: Test GitHub Actions" -ForegroundColor Yellow
Write-Host "------------------------------------------------------------" -ForegroundColor Gray
Write-Host ""
Write-Host "  1. Go to: https://github.com/Dialius/JastipHype/actions" -ForegroundColor White
Write-Host "  2. Click 'Deploy to Hostinger'" -ForegroundColor White
Write-Host "  3. Click 'Run workflow' -> 'Run workflow'" -ForegroundColor White
Write-Host "  4. Monitor the deployment" -ForegroundColor White
Write-Host ""

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "                  KEYS GENERATED!" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "For detailed guide, see: FIX_GITHUB_ACTIONS_SSH.md" -ForegroundColor Yellow
Write-Host ""
Write-Host "Key files saved in current directory:" -ForegroundColor White
Write-Host "  - $keyName (PRIVATE - for GitHub)" -ForegroundColor Red
Write-Host "  - $keyName.pub (PUBLIC - for Hostinger)" -ForegroundColor Green
Write-Host ""
Write-Host "IMPORTANT: Keep the PRIVATE key secure! Do NOT commit to Git!" -ForegroundColor Red
Write-Host ""
