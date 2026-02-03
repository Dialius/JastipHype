#!/bin/bash

# JastipHype - Vercel Deployment Helper Script
# This script helps prepare your app for Vercel deployment

echo "🚀 JastipHype - Vercel Deployment Helper"
echo "========================================"
echo ""

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ Error: npm is not installed"
    echo "Please install Node.js and npm first"
    exit 1
fi

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Error: composer is not installed"
    echo "Please install Composer first"
    exit 1
fi

echo "📦 Step 1: Installing dependencies..."
echo "------------------------------------"
composer install --no-dev --optimize-autoloader
npm install

echo ""
echo "🔨 Step 2: Building assets..."
echo "------------------------------------"
npm run build

if [ ! -d "public/build" ]; then
    echo "❌ Error: Build failed - public/build directory not found"
    exit 1
fi

echo ""
echo "🔑 Step 3: Checking APP_KEY..."
echo "------------------------------------"
if [ -f ".env" ]; then
    if grep -q "APP_KEY=base64:" .env; then
        echo "✅ APP_KEY is set"
    else
        echo "⚠️  Warning: APP_KEY is not set in .env"
        echo "Run: php artisan key:generate"
    fi
else
    echo "⚠️  Warning: .env file not found"
    echo "Copy .env.example to .env and configure it"
fi

echo ""
echo "✅ Build completed successfully!"
echo ""
echo "📋 Next Steps:"
echo "------------------------------------"
echo "1. Setup PostgreSQL database in Railway:"
echo "   → Go to https://railway.app"
echo "   → Create new project → Provision PostgreSQL"
echo "   → Copy database credentials"
echo ""
echo "2. Deploy to Vercel:"
echo "   → Go to https://vercel.com"
echo "   → Import your GitHub repository"
echo "   → Add environment variables (see VERCEL_DEPLOYMENT.md)"
echo "   → Deploy!"
echo ""
echo "3. Run database migrations:"
echo "   → Update local .env with Railway credentials"
echo "   → Run: php artisan migrate --force"
echo "   → Run: php artisan db:seed --force (optional)"
echo ""
echo "📖 For detailed instructions, see: VERCEL_DEPLOYMENT.md"
echo ""
