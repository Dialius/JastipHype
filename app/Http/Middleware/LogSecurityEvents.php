<?php

namespace App\Http\Middleware;

use App\Services\SecurityService;
use Closure;
use Illuminate\Http\Request;

class LogSecurityEvents
{
    protected $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log sensitive actions
        if (auth()->check()) {
            $sensitiveRoutes = [
                'password.update' => 'password_change',
                'profile.update' => 'profile_update',
                'user.email.update' => 'email_change',
            ];

            $routeName = $request->route()?->getName();
            
            if (isset($sensitiveRoutes[$routeName])) {
                $this->securityService->logSecurityEvent(
                    auth()->id(),
                    $sensitiveRoutes[$routeName]
                );
            }
        }

        return $response;
    }
}
