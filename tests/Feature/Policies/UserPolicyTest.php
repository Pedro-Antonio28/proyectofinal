<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;

uses(RefreshDatabase::class);

it('allows admin to delete other users', function () {
    $admin = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $admin->roles()->attach($role->id);

    $targetUser = User::factory()->create();

    $this->actingAs($admin);

    expect($admin->can('deleteUser', $targetUser))->toBeTrue();
});

it('denies admin from deleting themselves', function () {
    $admin = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $admin->roles()->attach($role->id);

    $this->actingAs($admin);

    expect($admin->can('deleteUser', $admin))->toBeFalse();
});

it('denies non-admin users from deleting users', function () {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();

    $this->actingAs($user);

    expect($user->can('deleteUser', $targetUser))->toBeFalse();
});
