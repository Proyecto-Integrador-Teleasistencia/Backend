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
        if (config('app.env') === 'production' && (request()->getHost() === 'back.projecte2.ddaw.es' || request()->getHost() === 'www.back.projecte2.ddaw.es')) {
            \URL::forceRootUrl(config('app.url'));
            \URL::forceScheme('https');
        }
        // \URL::forceScheme('https');
    }
}
