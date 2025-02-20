<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public const HOME = '/';

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer español en el sistema
        Carbon::setLocale('es'); // Carbon usa español
        App::setLocale('es'); // Laravel usa español
    }
}
