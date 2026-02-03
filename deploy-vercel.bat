@echo off
REM JastipHype - Vercel Deployment Helper Script (Windows)
REM This script helps prepare your app for Vercel deployment

echo.
echo ========================================
echo JastipHype - Vercel Deployment Helper
echo ========================================
echo.

REM Check if npm is installed
where npm >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: npm is not installed
    echo Please install Node.js and npm first
    exit /b 1
)

REM Check if composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: composer is not installed
    echo Please install Composer first
    exit /b 1
)

echo Step 1: Installing dependencies...
echo ------------------------------------
call composer install --no-dev --optimize-autoloader
call npm install

echo.
echo Step 2: Building assets...
echo ------------------------------------
call npm run build

if not exist "public\build" (
    echo Error: Build failed - public\build directory not found
    exit /b 1
)

echo.
echo Step 3: Checking APP_KEY...
echo ------------------------------------
if exist ".env" (
    findstr /C:"APP_KEY=base64:" .env >nul
    if %ERRORLEVEL% EQU 0 (
        echo APP_KEY is set
    ) else (
        echo Warning: APP_KEY is not set in .env
        echo Run: php artisan key:generate
    )
) else (
    echo Warning: .env file not found
    echo Copy .env.example to .env and configure it
)

echo.
echo Build completed successfully!
echo.
echo Next Steps:
echo ------------------------------------
echo 1. Setup PostgreSQL database in Railway:
echo    - Go to https://railway.app
echo    - Create new project - Provision PostgreSQL
echo    - Copy database credentials
echo.
echo 2. Deploy to Vercel:
echo    - Go to https://vercel.com
echo    - Import your GitHub repository
echo    - Add environment variables (see VERCEL_DEPLOYMENT.md)
echo    - Deploy!
echo.
echo 3. Run database migrations:
echo    - Update local .env with Railway credentials
echo    - Run: php artisan migrate --force
echo    - Run: php artisan db:seed --force (optional)
echo.
echo For detailed instructions, see: VERCEL_DEPLOYMENT.md
echo.
pause
