FROM richarvey/nginx-php-fpm:3.1.6

# Install Node.js for Vite build
RUN apk add --no-cache nodejs npm

# Copy application code
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Install npm dependencies and build assets
RUN npm ci && npm run build

# Set permissions for Laravel storage and cache
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create required directories
RUN mkdir -p /var/www/html/storage/framework/sessions
RUN mkdir -p /var/www/html/storage/framework/views
RUN mkdir -p /var/www/html/storage/framework/cache
RUN mkdir -p /var/www/html/storage/logs

# Copy custom nginx config (optional - uses default if not exists)
# COPY nginx.conf /etc/nginx/sites-available/default.conf

# Copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port
EXPOSE 80

# Start command
CMD ["/start.sh"]
