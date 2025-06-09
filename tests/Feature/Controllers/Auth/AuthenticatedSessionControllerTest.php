<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the login page', function () {
    $this->get(route('login'))
        ->assertStatus(200)
        ->assertViewIs('auth.login');
});

it('logs out the user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post(route('logout'))->assertRedirect(route('login'));


    $this->assertGuest();
});
