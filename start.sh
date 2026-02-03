#!/bin/bash

cd /var/www/html

# Generate storage link
php artisan storage:link || true

# Cache config & routes for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Start the main process (nginx + php-fpm via supervisor)
exec /usr/bin/supervisord -n -c /etc/supervisord.conf
