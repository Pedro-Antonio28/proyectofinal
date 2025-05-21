<?php

use App\Models\User;
use App\Models\Alimento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('shows the list of alimentos and user favorites', function () {
$user = User::factory()->create();
$alimento1 = Alimento::factory()->create();
$alimento2 = Alimento::factory()->create();

$user->alimentosFavoritos()->attach($alimento1->id);

actingAs($user)
->get(route('user.alimentos'))
->assertStatus(200)
->assertViewHas('alimentos')
->assertViewHas('favoritos', [$alimento1->id]);
});

it('stores user favorite alimentos', function () {
$user = User::factory()->create();
$alimento = Alimento::factory()->create();

actingAs($user)
->post(route('user.alimentos.store'), ['alimentos' => [$alimento->id]])
->assertRedirect(route('user.alimentos'));

$this->assertDatabaseHas('alimento_user', [
'user_id' => $user->id,
'alimento_id' => $alimento->id,
]);
});
