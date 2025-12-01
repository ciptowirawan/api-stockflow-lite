<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
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
        \Laravel\Passport\Passport::loadKeysFrom(storage_path());
        Passport::enablePasswordGrant();

        Passport::tokensExpireIn(Carbon::now()->addHours(6));
    }
}
