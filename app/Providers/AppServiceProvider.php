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
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        // Disable wrapping for resources
        \Illuminate\Http\Resources\Json\JsonResource::withoutWrapping();

        // Register Sidebar View Composer
        view()->composer('admin.layouts.sidebar', \App\Http\View\Composers\SidebarComposer::class);

        // Register Notification View Composer
        view()->composer('admin.layouts.header', \App\Http\View\Composers\NotificationComposer::class);
    }
}
