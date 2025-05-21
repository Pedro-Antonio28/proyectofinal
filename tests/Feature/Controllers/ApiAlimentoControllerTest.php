<?php

use App\Models\Alimento;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('retrieves all alimentos', function () {
    Alimento::factory()->count(3)->create();

    $this->getJson('/api/alimentos')
        ->assertStatus(200)
        ->assertJsonCount(3);
});

it('creates a new alimento', function () {
    Sanctum::actingAs(User::factory()->create()); // Autenticación con Sanctum

    $data = [
        'nombre' => 'Manzana',
        'calorias' => 52,
        'proteinas' => 0.3,
        'carbohidratos' => 14,
        'grasas' => 0.2
    ];

    $this->postJson('/api/alimentos', $data)
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Alimento creado con éxito',
            'alimento' => $data
        ]);

    $this->assertDatabaseHas('alimentos', $data);
});

it('shows a single alimento', function () {
    $alimento = Alimento::factory()->create();

    $this->getJson("/api/alimentos/{$alimento->id}")
        ->assertStatus(200)
        ->assertJson($alimento->toArray());
});

it('returns 404 if alimento not found', function () {
    $this->getJson('/api/alimentos/999')
        ->assertStatus(404)
        ->assertJsonStructure(['message']); // Permite cualquier mensaje de error
});

it('updates an alimento', function () {
    Sanctum::actingAs(User::factory()->create()); // Autenticación con Sanctum

    $alimento = Alimento::factory()->create();

    $data = ['nombre' => 'Pera'];

    $this->putJson("/api/alimentos/{$alimento->id}", $data)
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Alimento actualizado correctamente',
            'alimento' => array_merge($alimento->toArray(), $data)
        ]);

    $this->assertDatabaseHas('alimentos', ['id' => $alimento->id, 'nombre' => 'Pera']);
});

it('returns 404 when updating non-existing alimento', function () {
    Sanctum::actingAs(User::factory()->create()); // Autenticación con Sanctum

    $this->putJson('/api/alimentos/999', ['nombre' => 'Pera'])
        ->assertStatus(404)
        ->assertJsonStructure(['message']);
});

it('deletes an alimento', function () {
    Sanctum::actingAs(User::factory()->create()); // Autenticación con Sanctum

    $alimento = Alimento::factory()->create();

    $this->deleteJson("/api/alimentos/{$alimento->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Alimento eliminado correctamente']);

    $this->assertDatabaseMissing('alimentos', ['id' => $alimento->id]);
});

it('returns 404 when deleting non-existing alimento', function () {
    Sanctum::actingAs(User::factory()->create()); // Autenticación con Sanctum

    $this->deleteJson('/api/alimentos/999')
        ->assertStatus(404)
        ->assertJsonStructure(['message']);
});
