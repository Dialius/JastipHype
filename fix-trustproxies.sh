#!/bin/bash

# Fix TrustProxies middleware for Hostinger
# This fixes the "IpUtils::checkIp4(): Argument #2 ($ip) must be of type string, null given" error

LARAVEL_PATH="/home/u909490256/domains/jastiphype.shop"
MIDDLEWARE_PATH="$LARAVEL_PATH/app/Http/Middleware"

echo "Fixing TrustProxies middleware..."

# Create the fixed TrustProxies.php
cat > $MIDDLEWARE_PATH/TrustProxies.php << 'ENDOFFILE'
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // Set a default IP if none is provided (for CLI/cron jobs)
        if (!$request->server->has('REMOTE_ADDR')) {
            $request->server->set('REMOTE_ADDR', '127.0.0.1');
        }
        
        return parent::handle($request, $next);
    }
}
ENDOFFILE

echo "✓ TrustProxies.php updated"

# Clear caches
cd $LARAVEL_PATH
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo "✓ Caches cleared"

# Test the fix
echo ""
echo "Testing website..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://jastiphype.shop)
echo "HTTP Status Code: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    echo "✓ Website is now accessible!"
else
    echo "✗ Still getting HTTP $HTTP_CODE"
    echo ""
    echo "Checking Laravel logs for new errors..."
    tail -20 $LARAVEL_PATH/storage/logs/laravel.log | grep -i "error\|exception" | tail -5
fi
