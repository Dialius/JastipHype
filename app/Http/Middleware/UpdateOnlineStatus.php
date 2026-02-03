<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\OnlineUsersService;
use Symfony\Component\HttpFoundation\Response;

class UpdateOnlineStatus
{
    protected OnlineUsersService $onlineUsersService;

    public function __construct(OnlineUsersService $onlineUsersService)
    {
        $this->onlineUsersService = $onlineUsersService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update online status for authenticated users
        if (auth()->check()) {
            $this->onlineUsersService->trackActivity(auth()->id());
        }

        return $next($request);
    }
}
