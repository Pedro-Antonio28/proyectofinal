<?php

use App\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

it('sets locale from session', function () {
    Session::put('locale', 'es');

    $request = Request::create('/');
    $middleware = new SetLocale();

    $middleware->handle($request, function () {});

    expect(App::getLocale())->toBe('es');
});

it('does not change locale if session does not have locale', function () {
    Session::forget('locale');

    $request = Request::create('/');
    $middleware = new SetLocale();

    $middleware->handle($request, function () {});

    expect(App::getLocale())->toBe(config('app.locale'));
});
