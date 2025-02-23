<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Scopes\ExcludeAdminsScope;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Dieta;
use App\Policies\DietaPolicy;

class AuthServiceProvider extends ServiceProvider
{
protected $policies = [
    Dieta::class => DietaPolicy::class,
    User::class => UserPolicy::class,
];

public function boot()
{
    $this->registerPolicies();


}
}
