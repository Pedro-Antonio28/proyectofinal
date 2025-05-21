<?php

use App\Models\User;
use App\Models\Dieta;
use App\Models\Alimento;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows admin to view the user list', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1); // Suponiendo que 1 es el ID de admin

    $this->actingAs($admin)
        ->get(route('admin.users'))
        ->assertStatus(200)
        ->assertViewHas('users');
});

it('allows admin to edit a user', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1);
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $user->id))
        ->assertStatus(200)
        ->assertViewHas('user');
});

it('allows admin to update a user', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1);
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.users.update', $user->id), ['name' => 'Nuevo Nombre'])
        ->assertRedirect(route('admin.users'));

    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Nuevo Nombre']);
});

it('allows admin to delete a user', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1);
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $user->id))
        ->assertRedirect(route('admin.users'));

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('allows admin to view diet list', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1);
    Dieta::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.dietas'))
        ->assertStatus(200)
        ->assertViewHas('dietas');
});

it('allows admin to delete a diet', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1);
    $dieta = Dieta::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.dietas.destroy', $dieta->id))
        ->assertRedirect(route('admin.dietas'));

    $this->assertDatabaseMissing('dietas', ['id' => $dieta->id]);
});

