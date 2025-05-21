<?php

use App\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

uses(RefreshDatabase::class);

use Illuminate\Http\Exceptions\HttpResponseException;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Definir una ruta de prueba que usa el middleware
    \Illuminate\Support\Facades\Route::middleware(['role:admin'])->get('/admin', function () {
        return response('OK', 200);
    });
});

it('denies access to unauthenticated users', function () {
    Log::spy();

    $response = $this->get('/admin');

    $response->assertStatus(403);
    Log::shouldHaveReceived('error')->once()->with('Middleware Role: Usuario no autenticado');
});

it('denies access to users without the required role', function () {
    Log::spy();

    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/admin');

    $response->assertStatus(403);
    Log::shouldHaveReceived('error')->once();
});


it('allows access to users with the required role', function () {
    Log::spy();

    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);

    $user->roles()->attach($role->id); // Asignar un rol válido
    $this->actingAs($user);

    $request = Request::create('/admin', 'GET');
    $request->setUserResolver(fn () => $user);

    $middleware = new RoleMiddleware();
    $response = $middleware->handle($request, fn() => response('OK', 200), 'admin');

    expect($response->getStatusCode())->toBe(200);
    Log::shouldHaveReceived('info')->once();
});
