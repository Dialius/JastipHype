<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust Vercel proxies
        $middleware->trustProxies(at: '*');

        // Register admin middleware alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        
        // Register tracking middleware for web routes
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
            \App\Http\Middleware\UpdateOnlineStatus::class,
            \App\Http\Middleware\EnsureStorageDirectories::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle exceptions in Vercel serverless environment
        // Prevent View service errors from crashing the app
        $exceptions->render(function (\Throwable $e, $request) {
            // If View service is not available (serverless cold start)
            // Return JSON response instead of trying to render view
            if ($e instanceof \Illuminate\Contracts\Container\BindingResolutionException) {
                if (str_contains($e->getMessage(), 'view')) {
                    return response()->json([
                        'error' => 'Application initialization error',
                        'message' => 'The application is starting up. Please refresh in a moment.',
                        'details' => config('app.debug') ? $e->getMessage() : null,
                    ], 503);
                }
            }
            
            // For other errors, try to return JSON if view fails
            if (!app()->bound('view') || !config('view.compiled')) {
                return response()->json([
                    'error' => 'Server Error',
                    'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                ], 500);
            }
        });
    })->create();
