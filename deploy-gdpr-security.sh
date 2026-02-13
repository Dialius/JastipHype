#!/bin/bash

# GDPR & Security Deployment Script
# JastipHype - 2026

echo "🚀 Starting GDPR & Security Deployment..."
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to print success message
success() {
    echo -e "${GREEN}✓ $1${NC}"
}

# Function to print warning message
warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

# Function to print error message
error() {
    echo -e "${RED}✗ $1${NC}"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    error "Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

echo "Step 1: Running database migrations..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    success "Migrations completed successfully"
else
    error "Migration failed"
    exit 1
fi
echo ""

echo "Step 2: Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
success "Caches cleared"
echo ""

echo "Step 3: Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
success "Caches rebuilt"
echo ""

echo "Step 4: Creating storage directories..."
mkdir -p storage/app/gdpr-exports
chmod -R 775 storage/app/gdpr-exports
success "Storage directories created"
echo ""

echo "Step 5: Testing GDPR commands..."
php artisan list gdpr
if [ $? -eq 0 ]; then
    success "GDPR commands registered"
else
    warning "GDPR commands not found - check if they're registered"
fi
echo ""

echo "Step 6: Testing Security commands..."
php artisan list security
if [ $? -eq 0 ]; then
    success "Security commands registered"
else
    warning "Security commands not found - check if they're registered"
fi
echo ""

echo "Step 7: Checking database tables..."
php artisan tinker --execute="
    \$tables = [
        'cookie_consents',
        'data_export_requests',
        'data_deletion_requests',
        'login_attempts',
        'security_events',
        'blocked_ips',
        'user_sessions'
    ];
    foreach (\$tables as \$table) {
        if (Schema::hasTable(\$table)) {
            echo \"✓ Table \$table exists\n\";
        } else {
            echo \"✗ Table \$table missing\n\";
        }
    }
"
echo ""

echo "Step 8: Setting up cron jobs..."
echo ""
echo "Add these lines to your crontab (crontab -e):"
echo ""
echo "# GDPR & Security Cron Jobs"
echo "0 * * * * cd $(pwd) && php artisan gdpr:process-exports >> /dev/null 2>&1"
echo "0 0 * * * cd $(pwd) && php artisan gdpr:cleanup-exports >> /dev/null 2>&1"
echo "0 1 * * * cd $(pwd) && php artisan security:cleanup >> /dev/null 2>&1"
echo ""
warning "Please add these cron jobs manually"
echo ""

echo "Step 9: Checking environment variables..."
if grep -q "GDPR_EXPORT_EXPIRY_DAYS" .env; then
    success "GDPR environment variables found"
else
    warning "GDPR environment variables not found in .env"
    echo ""
    echo "Add these to your .env file:"
    echo ""
    echo "# GDPR Settings"
    echo "GDPR_EXPORT_EXPIRY_DAYS=7"
    echo "GDPR_RETENTION_PERIOD_YEARS=5"
    echo ""
    echo "# Security Settings"
    echo "SECURITY_MAX_LOGIN_ATTEMPTS=5"
    echo "SECURITY_LOGIN_LOCKOUT_MINUTES=60"
    echo "SECURITY_BLOCK_DURATION_MINUTES=60"
    echo ""
fi
echo ""

echo "Step 10: Running quick tests..."
echo ""
echo "Testing cookie consent component..."
if [ -f "resources/views/components/cookie-consent.blade.php" ]; then
    success "Cookie consent component exists"
else
    error "Cookie consent component missing"
fi

echo "Testing GDPR views..."
if [ -f "resources/views/gdpr/privacy-policy.blade.php" ]; then
    success "Privacy policy view exists"
else
    error "Privacy policy view missing"
fi

if [ -f "resources/views/gdpr/dashboard.blade.php" ]; then
    success "GDPR dashboard view exists"
else
    error "GDPR dashboard view missing"
fi

echo "Testing services..."
if [ -f "app/Services/GdprService.php" ]; then
    success "GdprService exists"
else
    error "GdprService missing"
fi

if [ -f "app/Services/SecurityService.php" ]; then
    success "SecurityService exists"
else
    error "SecurityService missing"
fi
echo ""

echo "═══════════════════════════════════════════════════════════"
echo ""
echo "🎉 Deployment completed!"
echo ""
echo "Next steps:"
echo "1. Visit your website and check if cookie banner appears"
echo "2. Login and visit /gdpr/dashboard"
echo "3. Test data export: php artisan gdpr:process-exports"
echo "4. Setup cron jobs (see above)"
echo "5. Review privacy policy content"
echo "6. Train admin team on GDPR management"
echo ""
echo "Documentation:"
echo "- Quick Start: GDPR_QUICKSTART.md"
echo "- Full Guide: GDPR_SECURITY_IMPLEMENTATION.md"
echo "- Security: SECURITY_CHECKLIST.md"
echo "- Summary: IMPLEMENTATION_SUMMARY.md"
echo ""
echo "Admin URLs:"
echo "- GDPR Management: /admin/gdpr"
echo "- Security Dashboard: /admin/security"
echo ""
echo "═══════════════════════════════════════════════════════════"
echo ""
success "All done! 🚀"
