<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{get, post, actingAs};

test('guest can see login page', function () {
    $response = get(route('login'));
    $response->assertStatus(200);
    $response->assertViewIs('auth.login');
});

test('authenticated user is redirected from login to dashboard', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = get(route('login'));
    $response->assertRedirect(route('dashboard'));
});

test('user can login with correct credentials and is redirected accordingly', function () {
    $user = User::factory()->create([
        'password' => bcrypt('12345678'),
        'peso' => 70,
        'altura' => 170,
        'age' => 25,
        'gender' => 'male',
        'objetivo' => 'bajar',
        'actividad' => 'media'
    ]);
    $user->alimentos()->attach([]); // ← o usa attach alimentos de ejemplo si necesitas

    $response = post(route('login'), [
        'email' => $user->email,
        'password' => '12345678',
    ]);

    $response->assertRedirect(route('user.alimentos')); // porque no tiene alimentos asociados
});

test('login redirects to questionnaire if user missing profile info', function () {
    $user = User::factory()->create([
        'password' => bcrypt('12345678'),
        'peso' => null, // falta info
    ]);

    $response = post(route('login'), [
        'email' => $user->email,
        'password' => '12345678',
    ]);

    $response->assertRedirect(route('questionnaire.show'));
});

test('login fails with incorrect credentials', function () {
    $user = User::factory()->create(['password' => bcrypt('secret123')]);

    $response = post(route('login'), [
        'email' => $user->email,
        'password' => 'wrongpass',
    ]);

    $response->assertSessionHasErrors('email');
});

test('user can logout and be redirected to login', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = post(route('logout'));
    $response->assertRedirect(route('login'));
    expect(Auth::check())->toBeFalse();
});

test('guest can see register page', function () {
    $response = get(route('register'));
    $response->assertStatus(200);
    $response->assertViewIs('auth.register');
});

test('user can register and is redirected to questionnaire', function () {
    $response = post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('questionnaire.show'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});
