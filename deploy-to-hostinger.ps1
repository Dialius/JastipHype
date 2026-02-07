# Deploy JastipHype to Hostinger
# Usage: .\deploy-to-hostinger.ps1

Write-Host "🚀 Starting deployment to Hostinger..." -ForegroundColor Green

$SSH_HOST = "195.35.62.164"
$SSH_PORT = "65002"
$SSH_USER = "u909490256"

$commands = @"
cd /home/u909490256/domains/jastiphype.shop
echo '📥 Pulling latest code...'
git pull origin master
echo '📦 Installing dependencies...'
composer install --no-dev --optimize-autoloader --no-interaction
echo '📁 Copying public files...'
cp -rf public/* public_html/
echo '🔐 Setting permissions...'
chmod -R 775 storage bootstrap/cache
chmod -R 755 public_html
echo '🗄️ Running migrations...'
php artisan migrate --force
echo '🧹 Clearing cache...'
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo '✅ Deployment completed!'
"@

ssh -p $SSH_PORT "$SSH_USER@$SSH_HOST" $commands

Write-Host "🎉 Done! Check https://jastiphype.shop" -ForegroundColor Green
