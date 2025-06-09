<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Dieta;
use App\Models\TelegramUser;
use App\Models\Post;
use App\Models\Alimento;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'peso' => 70,
        'altura' => 170,
        'age' => 25,
        'gender' => 'male',
        'actividad' => 'moderado',
        'objetivo' => 'ganar_musculo',
    ]);
});

test('it calculates daily calories correctly', function () {
    $calorias = $this->user->calcularCalorias();

    expect($calorias)->toBeNumeric();
    expect(round($calorias))->toBeGreaterThan(0);

});

test('it updates daily calories in database', function () {
    $this->user->actualizarCalorias();

    $this->user->refresh();

    expect($this->user->calorias_necesarias)->toBeInt();
});

test('user has roles and can check them', function () {
    $role = Role::factory()->create(['name' => 'nutricionista']);
    $this->user->roles()->attach($role);

    expect($this->user->hasRole('nutricionista'))->toBeTrue();
});

test('user has alimentos favoritos and normales', function () {
    $alimento = Alimento::factory()->create();
    $this->user->alimentos()->attach($alimento);

    expect($this->user->alimentos)->toHaveCount(1);
});

test('user has liked posts', function () {
    $post = Post::factory()->create();
    $this->user->likedPosts()->attach($post);

    expect($this->user->likedPosts)->toHaveCount(1);
});

test('user has telegram', function () {
    $telegram = TelegramUser::factory()->create(['user_id' => $this->user->id]);

    expect($this->user->telegram)->not->toBeNull();
});

test('user can have saved posts', function () {
    $post = Post::factory()->create();
    $this->user->postsWhoSavedIt()->attach($post, [
        'added_at' => now(),
        'custom_notes' => 'nota',
        'es_favorito' => true,
    ]);

    expect($this->user->postsWhoSavedIt)->toHaveCount(1);
});

test('user can be cliente de un nutricionista', function () {
    $nutricionista = User::factory()->create();
    $nutricionista->clientes()->attach($this->user);

    expect($this->user->nutricionista()->first()->id)->toBe($nutricionista->id);
});

test('user puede tener clientes si es nutricionista', function () {
    $cliente = User::factory()->create();
    $this->user->clientes()->attach($cliente);

    expect($this->user->clientes)->toHaveCount(1);
});
