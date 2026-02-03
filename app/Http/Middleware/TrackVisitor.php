<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\VisitorTrackingService;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    protected VisitorTrackingService $visitorTrackingService;

    public function __construct(VisitorTrackingService $visitorTrackingService)
    {
        $this->visitorTrackingService = $visitorTrackingService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for OAuth callback routes and other excluded paths
        $excludedPaths = [
            'auth/*/callback',
            'payment/webhook',
            'api/*',
        ];
        
        foreach ($excludedPaths as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }
        
        // Truncate very long URLs to prevent database errors
        $pageUrl = $request->fullUrl();
        if (strlen($pageUrl) > 500) {
            $pageUrl = substr($pageUrl, 0, 500) . '...';
        }
        
        // Log the visit
        try {
            $this->visitorTrackingService->logVisit(
                pageUrl: $pageUrl,
                userId: auth()->id(),
                sessionId: session()->getId()
            );
        } catch (\Exception $e) {
            // Silently fail to not break the request
            \Log::warning('Failed to log visitor: ' . $e->getMessage());
        }

        return $next($request);
    }
}
