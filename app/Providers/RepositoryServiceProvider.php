<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\Eloquent\ProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\OrderRepositoryInterface::class,
            \App\Repositories\Eloquent\OrderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CustomerRepositoryInterface::class,
            \App\Repositories\Eloquent\CustomerRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\BannerRepositoryInterface::class,
            \App\Repositories\Eloquent\BannerRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\DiscountRepositoryInterface::class,
            \App\Repositories\Eloquent\DiscountRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ReviewRepositoryInterface::class,
            \App\Repositories\Eloquent\ReviewRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\BrandRepositoryInterface::class,
            \App\Repositories\Eloquent\BrandRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\PaymentRepositoryInterface::class,
            \App\Repositories\Eloquent\PaymentRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ActivityLogRepositoryInterface::class,
            \App\Repositories\Eloquent\ActivityLogRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\SettingsRepositoryInterface::class,
            \App\Repositories\Eloquent\SettingsRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\VisitorLogRepositoryInterface::class,
            \App\Repositories\Eloquent\VisitorLogRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CustomerMessageRepositoryInterface::class,
            \App\Repositories\Eloquent\CustomerMessageRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CategoryRepositoryInterface::class,
            \App\Repositories\Eloquent\CategoryRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
