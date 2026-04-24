<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // 2. Paksa penggunaan HTTPS jika diakses melalui proxy/Ngrok
        if (str_contains(request()->header('x-forwarded-host'), 'ngrok') || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
