<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the user profile edit page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('profile.edit'))
        ->assertStatus(200)
        ->assertViewHas('user', $user);
});

it('updates the user profile', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $data = ['name' => 'New Name', 'email' => 'newemail@example.com'];

    $this->patch(route('profile.update'), $data)
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status', 'profile-updated');

    $user->refresh();

    expect($user->name)->toBe($data['name'])
        ->and($user->email)->toBe($data['email']);
});

it('deletes the user account', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);
    $this->actingAs($user);

    $this->from(route('profile.edit'))
        ->delete(route('profile.destroy'), ['password' => 'password'])
        ->assertRedirect('/');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
