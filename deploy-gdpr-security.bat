@echo off
REM GDPR & Security Deployment Script for Windows
REM JastipHype - 2026

echo.
echo ========================================
echo  GDPR ^& Security Deployment
echo  JastipHype - 2026
echo ========================================
echo.

REM Check if artisan exists
if not exist "artisan" (
    echo [ERROR] artisan file not found. Please run this script from the Laravel root directory.
    pause
    exit /b 1
)

echo Step 1: Running database migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo [ERROR] Migration failed
    pause
    exit /b 1
)
echo [OK] Migrations completed successfully
echo.

echo Step 2: Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo [OK] Caches cleared
echo.

echo Step 3: Rebuilding caches...
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo [OK] Caches rebuilt
echo.

echo Step 4: Creating storage directories...
if not exist "storage\app\gdpr-exports" mkdir storage\app\gdpr-exports
echo [OK] Storage directories created
echo.

echo Step 5: Testing GDPR commands...
php artisan list gdpr
if %errorlevel% equ 0 (
    echo [OK] GDPR commands registered
) else (
    echo [WARNING] GDPR commands not found - check if they're registered
)
echo.

echo Step 6: Testing Security commands...
php artisan list security
if %errorlevel% equ 0 (
    echo [OK] Security commands registered
) else (
    echo [WARNING] Security commands not found - check if they're registered
)
echo.

echo Step 7: Checking files...
echo.
if exist "resources\views\components\cookie-consent.blade.php" (
    echo [OK] Cookie consent component exists
) else (
    echo [ERROR] Cookie consent component missing
)

if exist "resources\views\gdpr\privacy-policy.blade.php" (
    echo [OK] Privacy policy view exists
) else (
    echo [ERROR] Privacy policy view missing
)

if exist "resources\views\gdpr\dashboard.blade.php" (
    echo [OK] GDPR dashboard view exists
) else (
    echo [ERROR] GDPR dashboard view missing
)

if exist "app\Services\GdprService.php" (
    echo [OK] GdprService exists
) else (
    echo [ERROR] GdprService missing
)

if exist "app\Services\SecurityService.php" (
    echo [OK] SecurityService exists
) else (
    echo [ERROR] SecurityService missing
)
echo.

echo Step 8: Setting up scheduled tasks...
echo.
echo For Windows Task Scheduler, create these tasks:
echo.
echo Task 1: Process GDPR Exports (Hourly)
echo   Program: php
echo   Arguments: artisan gdpr:process-exports
echo   Start in: %CD%
echo   Trigger: Daily, repeat every 1 hour
echo.
echo Task 2: Cleanup Expired Exports (Daily)
echo   Program: php
echo   Arguments: artisan gdpr:cleanup-exports
echo   Start in: %CD%
echo   Trigger: Daily at 00:00
echo.
echo Task 3: Security Cleanup (Daily)
echo   Program: php
echo   Arguments: artisan security:cleanup
echo   Start in: %CD%
echo   Trigger: Daily at 01:00
echo.
echo [WARNING] Please create these scheduled tasks manually
echo.

echo Step 9: Checking environment variables...
findstr /C:"GDPR_EXPORT_EXPIRY_DAYS" .env >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] GDPR environment variables found
) else (
    echo [WARNING] GDPR environment variables not found in .env
    echo.
    echo Add these to your .env file:
    echo.
    echo # GDPR Settings
    echo GDPR_EXPORT_EXPIRY_DAYS=7
    echo GDPR_RETENTION_PERIOD_YEARS=5
    echo.
    echo # Security Settings
    echo SECURITY_MAX_LOGIN_ATTEMPTS=5
    echo SECURITY_LOGIN_LOCKOUT_MINUTES=60
    echo SECURITY_BLOCK_DURATION_MINUTES=60
    echo.
)
echo.

echo ========================================
echo.
echo Deployment completed!
echo.
echo Next steps:
echo 1. Visit your website and check if cookie banner appears
echo 2. Login and visit /gdpr/dashboard
echo 3. Test data export: php artisan gdpr:process-exports
echo 4. Setup scheduled tasks (see above)
echo 5. Review privacy policy content
echo 6. Train admin team on GDPR management
echo.
echo Documentation:
echo - Quick Start: GDPR_QUICKSTART.md
echo - Full Guide: GDPR_SECURITY_IMPLEMENTATION.md
echo - Security: SECURITY_CHECKLIST.md
echo - Summary: IMPLEMENTATION_SUMMARY.md
echo.
echo Admin URLs:
echo - GDPR Management: /admin/gdpr
echo - Security Dashboard: /admin/security
echo.
echo ========================================
echo.
echo [OK] All done!
echo.
pause
