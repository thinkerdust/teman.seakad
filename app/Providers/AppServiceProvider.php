<?php

namespace App\Providers;

use App\Http\View\Composers\NotificationComposer;
use App\Http\View\Composers\SidebarComposer;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/helpers.php');
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
        JsonResource::withoutWrapping();

        // Register Sidebar View Composer
        view()->composer('admin.layouts.sidebar', SidebarComposer::class);

        // Register Notification View Composer
        view()->composer('admin.layouts.header', NotificationComposer::class);
    }
}
