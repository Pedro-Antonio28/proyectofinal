<?php

use Illuminate\Support\Facades\Route;

it('sets the home route constant', function () {
    expect(\App\Providers\RouteServiceProvider::HOME)->toBe('/seleccionar-alimentos');
});

it('registers web routes', function () {
    $this->app->register(\App\Providers\RouteServiceProvider::class);

    expect(Route::getRoutes())->not->toBeEmpty();
});
