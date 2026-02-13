#!/bin/bash

# Production Deployment Script for GDPR & Security
# Run this on the server via SSH

echo "🚀 Deploying GDPR & Security to Production..."
echo ""

# Navigate to project directory
cd ~/domains/jastiphype.shop || exit 1

# Pull latest changes
echo "📥 Pulling latest changes from GitHub..."
git pull origin master

# Install/update dependencies (if needed)
# composer install --no-dev --optimize-autoloader

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild caches
echo "🔨 Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage directory for GDPR exports
echo "📁 Creating storage directories..."
mkdir -p storage/app/gdpr-exports
chmod -R 775 storage/app/gdpr-exports

# Copy public files (if needed)
# cp -rf public/* public_html/

echo ""
echo "✅ Deployment completed!"
echo ""
echo "Next steps:"
echo "1. Visit https://jastiphype.shop and check cookie banner"
echo "2. Test GDPR dashboard: https://jastiphype.shop/gdpr/dashboard"
echo "3. Test privacy policy: https://jastiphype.shop/gdpr/privacy-policy"
echo "4. Setup cron jobs (see GDPR_QUICKSTART.md)"
echo ""
echo "Cron jobs to add:"
echo "0 * * * * cd ~/domains/jastiphype.shop && php artisan gdpr:process-exports"
echo "0 0 * * * cd ~/domains/jastiphype.shop && php artisan gdpr:cleanup-exports"
echo "0 1 * * * cd ~/domains/jastiphype.shop && php artisan security:cleanup"
echo ""
