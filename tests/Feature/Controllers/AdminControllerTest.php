<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminRole = Role::factory()->create(['name' => 'admin']);
});

it('allows admin to view the user list', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach($this->adminRole->id);

    $this->actingAs($admin)
        ->get(route('admin.users'))
        ->assertStatus(200)
        ->assertViewHas('usuarios');
});

it('allows admin to update a user', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach($this->adminRole->id);
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.update', $user->id), ['email' => 'newemail@example.com'])
        ->assertRedirect(route('admin.users'));


    $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'newemail@example.com']);
});

it('allows admin to delete a user', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach($this->adminRole->id);
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.delete', $user->id))
        ->assertRedirect(route('admin.users'));


    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
