<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Review::observe(\App\Observers\ReviewObserver::class);
        \App\Models\Brand::observe(\App\Observers\BrandObserver::class);
        \App\Models\CustomerMessage::observe(\App\Observers\CustomerMessageObserver::class);
        \App\Models\VisitorLog::observe(\App\Observers\VisitorLogObserver::class);

        // Share admin notifications with all admin views
        view()->composer('admin.layouts.navbar', function ($view) {
            if (auth()->check() && auth()->user()->is_admin) {
                $notificationService = app(\App\Services\AdminNotificationService::class);
                $view->with('adminNotifications', $notificationService->getNotifications(10));
                $view->with('adminNotificationCount', $notificationService->getUnreadCount());
            }
        });
    }
}
