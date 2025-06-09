<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;
use function Pest\Laravel\get;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\assertAuthenticated;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

use function Pest\Laravel\{post};

beforeEach(function () {
    RateLimiter::clear('*');
});
beforeEach(function () {
    $this->user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

test('authenticated user can view profile page', function () {
    actingAs($this->user);

    get(route('profile.edit'))
        ->assertStatus(200)
        ->assertSee($this->user->email);
});

test('user can update profile with valid data', function () {
    actingAs($this->user);

    patch(route('profile.update'), [
        'name' => 'Nuevo Nombre',
        'email' => 'nuevoemail@example.com',
    ])->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status', 'profile-updated');

    expect($this->user->fresh()->name)->toBe('Nuevo Nombre');
    expect($this->user->fresh()->email)->toBe('nuevoemail@example.com');
    expect($this->user->fresh()->email_verified_at)->toBeNull(); // Verificación reiniciada
});

test('email must be valid and unique (except current user)', function () {
    User::factory()->create(['email' => 'existing@example.com']);
    actingAs($this->user);

    patch(route('profile.update'), [
        'email' => 'existing@example.com',
    ])->assertSessionHasErrors('email');
});

test('name is required and must be a string within max length', function () {
    actingAs($this->user);

    patch(route('profile.update'), [
        'name' => '',
    ])->assertSessionHasErrors('name');

    patch(route('profile.update'), [
        'name' => str_repeat('a', 256),
    ])->assertSessionHasErrors('name');
});

test('email is required and must be valid format', function () {
    actingAs($this->user);

    patch(route('profile.update'), [
        'email' => 'invalid-email',
    ])->assertSessionHasErrors('email');
});

test('unauthenticated users cannot access profile routes', function () {
    assertGuest();

    get(route('profile.edit'))->assertRedirect(route('login'));
    patch(route('profile.update'))->assertRedirect(route('login'));
});

test('un usuario puede iniciar sesión con credenciales válidas', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('secret123'),
    ]);

    post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'secret123',
    ])->assertRedirect(route('questionnaire.show'));

    $this->assertAuthenticatedAs($user);
});

test('login falla con credenciales incorrectas', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('correct-password'),
    ]);

    post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('email y password son requeridos para login', function () {
    post(route('login'), [
        'email' => '',
        'password' => '',
    ])->assertSessionHasErrors(['email', 'password']);
});

test('email debe tener formato válido', function () {
    post(route('login'), [
        'email' => 'no-es-un-email',
        'password' => '12345678',
    ])->assertSessionHasErrors('email');
});



test('login bloqueado tras múltiples intentos fallidos', function () {
    $email = 'bloqueado@example.com';
    $ip = '127.0.0.1';

    RateLimiter::clear(Str::lower($email).'|'.$ip);

    for ($i = 0; $i < 6; $i++) {
        post(route('login'), [
            'email' => $email,
            'password' => 'contraseña-inválida',
        ]);
    }

    post(route('login'), [
        'email' => $email,
        'password' => 'contraseña-inválida',
    ])->assertSessionHasErrors('email')
        ->assertSessionHas('errors');

    $this->assertGuest();
});

