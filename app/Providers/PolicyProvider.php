<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PolicyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register model policy
        // Gate::policy(Model::class, ModelPolicy::class);
    }
}
