<?php

namespace App\Providers;

use App\Actions\StoreNotificationTypes;
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
        if (env('APP_ENV') === 'local') {
            (new StoreNotificationTypes)();
        }
    }
}