<?php

namespace App\Providers;

use App\Listeners\LogEmail;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class TestingProvider extends ServiceProvider
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
        if ($this->app->runningUnitTests()) {
            // Register events listener
            Event::listen(MessageSending::class, LogEmail::class);

            // Mail
            Mail::alwaysTo('test@github.com');
        }
    }
}
