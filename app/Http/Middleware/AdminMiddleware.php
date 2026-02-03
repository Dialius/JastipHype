<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Validates that the authenticated user has administrator privileges.
     * Non-admin users are redirected to an unauthorized page.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access the admin panel.');
        }

        // Check if user has admin privileges
        if (!auth()->user()->is_admin) {
            return redirect()->route('unauthorized')
                ->with('error', 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
