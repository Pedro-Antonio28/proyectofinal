<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a role', function () {
    $role = Role::factory()->create(['name' => 'admin']);

    expect($role)->toBeInstanceOf(Role::class)
        ->and($role->name)->toBe('admin');
});

it('can associate a role with a user', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);

    // Asociar el usuario al rol
    $user->roles()->attach($role);

    // Verificar la relación
    expect($user->roles->contains($role))->toBeTrue();
});
