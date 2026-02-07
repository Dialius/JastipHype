#!/bin/bash

echo "=== FORCE FIX NOW ==="
echo ""

# 1. Stash local changes
echo "📦 Stashing local changes..."
git stash

# 2. Pull latest code
echo "📥 Pulling latest code..."
git pull origin master

# 3. Copy .env.hostinger to .env
echo "📝 Updating .env..."
cp .env.hostinger .env

# 4. Show database config
echo ""
echo "🔍 Database config in .env:"
grep "DB_DATABASE" .env
grep "DB_USERNAME" .env
echo ""

# 5. Remove ALL cache files
echo "🗑️  Removing ALL cache files..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*
rm -rf storage/framework/sessions/*

# 6. Clear config cache (skip database cache)
echo "🧹 Clearing config..."
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 7. Rebuild config cache
echo "💾 Rebuilding config cache..."
php artisan config:cache

# 8. Test database connection
echo ""
echo "🧪 Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully!'; } catch (Exception \$e) { echo 'Database error: ' . \$e->getMessage(); }"

echo ""
echo "✅ DONE!"
echo ""
echo "📝 Now refresh browser: https://jastiphype.shop"
