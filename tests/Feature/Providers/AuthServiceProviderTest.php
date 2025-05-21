<?php

use App\Models\User;
use App\Models\Dieta;
use App\Policies\UserPolicy;
use App\Policies\DietaPolicy;
use Illuminate\Support\Facades\Gate;

it('registers policies', function () {
    $this->app->register(\App\Providers\AuthServiceProvider::class);

    expect(Gate::getPolicyFor(User::class))->toBeInstanceOf(UserPolicy::class);
    expect(Gate::getPolicyFor(Dieta::class))->toBeInstanceOf(DietaPolicy::class);
});

