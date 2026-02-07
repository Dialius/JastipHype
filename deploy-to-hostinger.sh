#!/bin/bash

# Deploy JastipHype to Hostinger
# Usage: ./deploy-to-hostinger.sh

echo "🚀 Starting deployment to Hostinger..."

# SSH connection details
SSH_HOST="195.35.62.164"
SSH_PORT="65002"
SSH_USER="u909490256"
DEPLOY_PATH="/home/u909490256/domains/jastiphype.shop"

# Connect and deploy
ssh -p $SSH_PORT $SSH_USER@$SSH_HOST << 'ENDSSH'
cd /home/u909490256/domains/jastiphype.shop

echo "📥 Pulling latest code..."
git pull origin master

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "📁 Copying public files..."
cp -rf public/* public_html/

echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 755 public_html

echo "🗄️ Running migrations..."
php artisan migrate --force

echo "🧹 Clearing cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed!"
ENDSSH

echo "🎉 Done! Check https://jastiphype.shop"
