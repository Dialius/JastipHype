<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ServerlessCompatibilityService;

class CheckServerlessCompatibility
{
    protected ServerlessCompatibilityService $serverlessService;
    
    public function __construct(ServerlessCompatibilityService $serverlessService)
    {
        $this->serverlessService = $serverlessService;
    }
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Add serverless info to view
        if ($this->serverlessService->isServerless()) {
            view()->share('isServerless', true);
            view()->share('serverlessRecommendations', $this->serverlessService->getServerlessRecommendations());
        } else {
            view()->share('isServerless', false);
            view()->share('serverlessRecommendations', []);
        }
        
        return $next($request);
    }
}
