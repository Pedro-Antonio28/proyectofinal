<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it has name fillable', function () {
    $role = new Role(['name' => 'admin']);
    expect($role->name)->toBe('admin');
});

test('it can be created with name', function () {
    $role = Role::create(['name' => 'moderador']);
    expect($role)->toBeInstanceOf(Role::class);
    expect($role->name)->toBe('moderador');
});

test('it belongs to many users', function () {
    $role = Role::factory()->create();
    $users = User::factory()->count(2)->create();

    $role->users()->attach($users->pluck('id'));

    expect($role->users)->toHaveCount(2);
});
