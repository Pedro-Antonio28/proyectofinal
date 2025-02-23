<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;

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
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            $locale = Config::get('app.locale'); // Usar el idioma por defecto si no está en sesión
            Session::put('locale', $locale);
        }

        App::setLocale($locale);
        Config::set('app.locale', $locale);

        \Log::info('Idioma cargado en AppServiceProvider: ' . $locale);
    }


}
