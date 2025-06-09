<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'sanctum');
});

test('authenticated user can list posts', function () {
    Post::factory()->count(3)->create();

    $response = $this->getJson('/api/posts');

    $response->assertOk()->assertJsonStructure(['data']);
});

test('authenticated user can view a single post', function () {
    $post = Post::factory()->create();

    $response = $this->getJson("/api/posts/{$post->id}");

    $response->assertOk()->assertJsonFragment(['id' => $post->id]);
});

test('authenticated user can create a post', function () {
    $data = [
        'title' => 'Post de prueba',
        'description' => 'Una descripción válida',
        'macros' => [
            'calories' => 500,
            'protein' => 30,
            'carbs' => 60,
            'fat' => 10,
        ],
    ];

    $response = $this->postJson('/api/posts', $data);

    $response->assertCreated()
        ->assertJsonFragment(['title' => 'Post de prueba']);

    $this->assertDatabaseHas('posts', ['title' => 'Post de prueba']);
});

test('authenticated user can update own post', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);

    $response = $this->putJson("/api/posts/{$post->id}", [
        'title' => 'Nuevo título',
        'description' => 'Descripción actualizada',
        'macros' => [
            'calories' => 600,
            'protein' => 40,
            'carbs' => 50,
            'fat' => 15,
        ],
    ]);

    $response->assertOk()
        ->assertJsonFragment(['title' => 'Nuevo título']);
});

test('authenticated user cannot update post of another user', function () {
    $post = Post::factory()->create(); // otro usuario

    $response = $this->putJson("/api/posts/{$post->id}", [
        'title' => 'Cambio ilegal',
        'description' => 'Intentando cambiar otro post',
        'macros' => [
            'calories' => 200,
            'protein' => 10,
            'carbs' => 20,
            'fat' => 5,
        ],
    ]);

    $response->assertForbidden();
});

test('authenticated user can delete own post', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);

    $response = $this->deleteJson("/api/posts/{$post->id}");

    $response->assertOk()
        ->assertJsonFragment(['message' => 'Post eliminado correctamente']);

    $this->assertSoftDeleted('posts', ['id' => $post->id]);

});

test('authenticated user cannot delete post of another user', function () {
    $post = Post::factory()->create(); // otro user

    $response = $this->deleteJson("/api/posts/{$post->id}");

    $response->assertForbidden();
});
