<?php

use App\Models\User;
use App\Models\Dieta;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('authenticated user can list own dietas', function () {
    Dieta::factory()->count(2)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/dietas');

    $response->assertOk();
    $response->assertJsonCount(2);
});

test('authenticated user can create a dieta', function () {
    $response = $this->postJson('/api/dietas', [
        'semana' => 1,
        'dieta' => ['lunes' => ['comida' => 'arroz']],
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('dietas', [
        'user_id' => $this->user->id,
        'semana' => 1,
    ]);
});

test('authenticated user can view own dieta', function () {
    $dieta = Dieta::factory()->create(['user_id' => $this->user->id]);

    $response = $this->getJson("/api/dietas/{$dieta->id}");

    $response->assertOk()->assertJsonFragment(['id' => $dieta->id]);
});

test('authenticated user can update own dieta', function () {
    $dieta = Dieta::factory()->create(['user_id' => $this->user->id, 'semana' => 1]);

    $response = $this->putJson("/api/dietas/{$dieta->id}", [
        'semana' => 2,
        'dieta' => ['martes' => ['comida' => 'pollo']],
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('dietas', ['id' => $dieta->id, 'semana' => 2]);
});

test('authenticated user can delete own dieta', function () {
    $dieta = Dieta::factory()->create(['user_id' => $this->user->id]);

    $response = $this->deleteJson("/api/dietas/{$dieta->id}");

    $response->assertOk()->assertJsonFragment(['message' => 'Dieta eliminada correctamente']);
    $this->assertDatabaseMissing('dietas', ['id' => $dieta->id]);
});

test('user cannot access another user dieta', function () {
    $otherUser = User::factory()->create();
    $dieta = Dieta::factory()->create(['user_id' => $otherUser->id]);

    $this->getJson("/api/dietas/{$dieta->id}")->assertForbidden();
    $this->putJson("/api/dietas/{$dieta->id}", ['semana' => 3])->assertForbidden();
    $this->deleteJson("/api/dietas/{$dieta->id}")->assertForbidden();
});
