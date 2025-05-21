<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows authenticated users to access the food selection page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('user.alimentos'));

    $response->assertStatus(200);
    $response->assertSeeLivewire('user-alimentos'); // Verifica que el componente está presente en la respuesta.

});

it('prevents unauthenticated users from accessing the food selection page', function () {
    $this->get(route('user.alimentos'))
        ->assertRedirect(route('login'));
});
