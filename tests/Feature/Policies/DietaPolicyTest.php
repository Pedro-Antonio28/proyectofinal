<?php

use App\Models\User;
use App\Models\Dieta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;

uses(RefreshDatabase::class);

it('allows admin to view any diet', function () {
    $admin = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $admin->roles()->attach($role->id);

    $dieta = Dieta::factory()->create();

    $this->actingAs($admin);

    expect($admin->can('view', $dieta))->toBeTrue();
});



it('denies nutritionist from viewing diets of non-clients', function () {
    $nutritionist = User::factory()->create();
    $anotherClient = User::factory()->create();

    $dieta = Dieta::factory()->create(['user_id' => $anotherClient->id]);

    $this->actingAs($nutritionist);

    expect($nutritionist->can('view', $dieta))->toBeFalse();
});

it('allows admin to edit any diet', function () {
    $admin = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $admin->roles()->attach($role->id);

    $dieta = Dieta::factory()->create();

    $this->actingAs($admin);

    expect($admin->can('update', $dieta))->toBeTrue();
});

it('denies nutritionist from editing diets of non-clients', function () {
    $nutritionist = User::factory()->create();
    $anotherClient = User::factory()->create();

    $dieta = Dieta::factory()->create(['user_id' => $anotherClient->id]);

    $this->actingAs($nutritionist);

    expect($nutritionist->can('update', $dieta))->toBeFalse();
});

it('allows admin to delete any diet', function () {
    $admin = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $admin->roles()->attach($role->id);

    $dieta = Dieta::factory()->create();

    $this->actingAs($admin);

    expect($admin->can('delete', $dieta))->toBeTrue();
});

it('denies nutritionist from deleting diets of non-clients', function () {
    $nutritionist = User::factory()->create();
    $anotherClient = User::factory()->create();

    $dieta = Dieta::factory()->create(['user_id' => $anotherClient->id]);

    $this->actingAs($nutritionist);

    expect($nutritionist->can('delete', $dieta))->toBeFalse();
});
