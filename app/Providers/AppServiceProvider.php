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
        Passport::enablePasswordGrant();

        Passport::tokensExpireIn(new \DateInterval('PT6H'));
        Passport::refreshTokensExpireIn(new \DateInterval('P30D'));
        Passport::personalAccessTokensExpireIn(new \DateInterval('P6M'));
    }
}
