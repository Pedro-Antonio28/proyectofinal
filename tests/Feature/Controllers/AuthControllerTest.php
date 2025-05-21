<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\{actingAs, post, get};

it('allows a user to view login page', function () {
    get(route('login'))
        ->assertStatus(200)
        ->assertSee('Iniciar sesión'); // Ajusta según el contenido de la vista
});

it('allows a user to login with correct credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123')
    ]);

    post(route('login.post'), [
        'email' => $user->email,
        'password' => 'password123'
    ])->assertRedirect(route('dashboard'));

    actingAs($user);
    expect(auth()->check())->toBeTrue();
});

it('prevents login with incorrect credentials', function () {
    post(route('login.post'), [
        'email' => 'wrong@example.com',
        'password' => 'wrongpassword'
    ])->assertSessionHasErrors('email');
});

it('logs out a user', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('logout'))->assertRedirect(route('login'));

    expect(auth()->check())->toBeFalse();
});

it('allows a user to view register page', function () {
    get(route('register'))
        ->assertStatus(200)
        ->assertSee('Registro');
});

it('allows a user to register', function () {
    post(route('register.post'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('login'));

    expect(User::where('email', 'test@example.com')->exists())->toBeTrue();
});
