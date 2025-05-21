<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the login page', function () {
    $this->get(route('login'))
        ->assertStatus(200)
        ->assertViewIs('auth.login');
});

it('allows users to login', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('logs out the user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post(route('logout'))->assertRedirect(route('login'));


    $this->assertGuest();
});
