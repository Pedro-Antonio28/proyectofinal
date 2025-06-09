<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('user can see the profile edit form', function () {
    $response = $this->get(route('profile.edit'));

    $response->assertOk();
    $response->assertSee($this->user->name);
});

test('user can update profile information', function () {
    $response = $this->patch(route('profile.update'), [
        'name' => 'Nuevo Nombre',
        'email' => 'nuevo@example.com',
    ]);


    $response->assertRedirect(route('profile.edit'));
    $this->assertEquals('Nuevo Nombre', $this->user->fresh()->name);
});

test('user can update password', function () {
    $response = $this->put(route('profile.update-password'), [
        'password' => 'nuevacontra123',
        'password_confirmation' => 'nuevacontra123',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'password-updated');

    $this->assertTrue(Hash::check('nuevacontra123', $this->user->fresh()->password));
});

test('unauthenticated user cannot access profile edit', function () {
    auth()->logout(); // Asegura que no haya sesión

    $response = $this->get(route('profile.edit'));
    $response->assertStatus(302); // cualquier redirección

    // Ya no asumimos login, solo que redirige a algo no accesible sin auth
    $this->assertTrue(
        str_contains($response->headers->get('Location'), 'login'),
        'La redirección no va al login'
    );

    $response = $this->patch(route('profile.update'), [
        'name' => 'Test',
        'email' => 'test@example.com',
    ]);
    $response->assertRedirect();
    $this->assertTrue(
        str_contains($response->headers->get('Location'), 'login'),
        'La redirección del patch no va al login'
    );

    $response = $this->put(route('profile.update-password'), [
        'current_password' => 'fake',
        'password' => 'fake2',
        'password_confirmation' => 'fake2',
    ]);
    $response->assertRedirect();
    $this->assertTrue(
        str_contains($response->headers->get('Location'), 'login'),
        'La redirección del password update no va al login'
    );
});

