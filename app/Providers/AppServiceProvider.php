<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Agrega esta línea arriba


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
       // Forzar HTTPS cuando la app esté en producción (como en Render)
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
        }
    }
}