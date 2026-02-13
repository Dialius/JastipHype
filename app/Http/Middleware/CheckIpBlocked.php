<?php

namespace App\Http\Middleware;

use App\Services\SecurityService;
use Closure;
use Illuminate\Http\Request;

class CheckIpBlocked
{
    protected $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->securityService->isIpBlocked($request->ip())) {
            abort(403, 'Your IP address has been blocked due to suspicious activity.');
        }

        return $next($request);
    }
}
