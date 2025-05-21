<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

it('sets the locale from session if available', function () {
    Session::put('locale', 'es');

    $this->app->register(\App\Providers\AppServiceProvider::class);

    expect(App::getLocale())->toBe('es');
});

it('sets the default locale if session is empty', function () {
    Session::forget('locale');
    Config::set('app.locale', 'fr');

    $this->app->register(\App\Providers\AppServiceProvider::class);

    expect(App::getLocale())->toBe('fr');
});
