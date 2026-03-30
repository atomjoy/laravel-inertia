<?php

namespace App\Providers;

use App\Http\Middleware\AdminAllowedIps;
use App\Http\Middleware\LogRequest;
use Illuminate\Support\ServiceProvider;

class AccessProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Log requests
        $this->app['router']->aliasMiddleware('log_requests', LogRequest::class);

        // Admin routes access
        $this->app['router']->aliasMiddleware('admin_allowed_ips', AdminAllowedIps::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
