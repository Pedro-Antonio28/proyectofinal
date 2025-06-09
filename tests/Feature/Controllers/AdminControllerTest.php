<?php

use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Role;
use function Pest\Laravel\{get, actingAs};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use function Pest\Laravel\{post, delete};


beforeEach(function () {
    // Crear usuario y asignar el rol "admin"
    $this->admin = User::factory()->create();
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $this->admin->roles()->attach($adminRole);

    actingAs($this->admin);
});

test('admin can view user list', function () {
    $response = get(route('admin.users'));
    $response->assertOk();
});

test('admin can view edit user page', function () {
    $user = User::factory()->create();
    $response = get(route('admin.users.edit', $user->id));
    $response->assertOk();
});

test('admin can view user diet page', function () {
    $user = User::factory()->create();
    $dieta = Dieta::factory()->create(['user_id' => $user->id]);
    $response = get(route('admin.users.dieta', $user->id));
    $response->assertOk();
});

test('admin can view edit food page', function () {
    $user = User::factory()->create();
    $dieta = Dieta::factory()->create(['user_id' => $user->id]);
    $alimento = DietaAlimento::factory()->create(['dieta_id' => $dieta->id]);
    $response = get(route('admin.dieta.editar-alimento', $alimento->id));
    $response->assertOk();
});

test('admin can delete a user', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::firstOrCreate(['name' => 'usuario']));

    $response = delete(route('admin.users.delete', $user->id));

    $response->assertRedirect(route('admin.users'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('admin can update user email', function () {
    $user = User::factory()->create(['email' => 'old@example.com']);

    $response = post(route('admin.users.update.email', $user->id), [
        'email' => 'new@example.com',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'new@example.com']);
});

test('admin can update user password', function () {
    $user = User::factory()->create(['password' => bcrypt('oldpass')]);

    $response = post(route('admin.users.update.password', $user->id), [
        'password' => 'newpassword123',
    ]);

    $response->assertRedirect();
    $user->refresh();
    expect(Hash::check('newpassword123', $user->password))->toBeTrue();
});

test('admin can delete a diet', function () {
    $dieta = \App\Models\Dieta::factory()->create();

    $response = delete(route('admin.dieta.delete', $dieta->id));

    $response->assertRedirect(route('admin.users'));
    $this->assertDatabaseMissing('dietas', ['id' => $dieta->id]);
});

test('admin can update food quantity in diet', function () {
    $dieta = \App\Models\Dieta::factory()->create();
    $alimento = \App\Models\DietaAlimento::factory()->create([
        'dieta_id' => $dieta->id,
        'cantidad' => 100,
    ]);

    $response = post(route('admin.dieta.update-alimento', $alimento->id), [
        'cantidad' => 250,
    ]);

    $response->assertRedirect(route('admin.users.dieta', $dieta->user_id));
    $this->assertDatabaseHas('dieta_alimentos', [
        'id' => $alimento->id,
        'cantidad' => 250,
    ]);
});
