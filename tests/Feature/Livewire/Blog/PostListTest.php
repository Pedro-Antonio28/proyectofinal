<?php

use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Blog\PostList;
use App\Http\Livewire\Blog\PostCard;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->post = Post::factory()->create(['user_id' => $this->user->id]);
});


test('post list renders correctly', function () {
    Post::factory()->count(3)->create();

    Livewire::test(PostList::class)
        ->assertStatus(200)
        ->assertSee('Dietas del Blog');
});

test('can toggle like on post', function () {
    $post = Post::factory()->create();

    $component = Livewire::actingAs($this->user)->test(PostList::class);

    $component->call('toggleLike', $post->id);

    $this->assertDatabaseHas('likes', [
        'user_id' => $this->user->id,
        'post_id' => $post->id,
    ]);

    $component->call('toggleLike', $post->id);

    $this->assertDatabaseMissing('likes', [
        'user_id' => $this->user->id,
        'post_id' => $post->id,
    ]);
});


test('can delete post if authorized', function () {
    $post = Post::factory()->for($this->user)->create();

    Livewire::actingAs($this->user)
        ->test(PostList::class)
        ->call('delete', $post->id);

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});


test('authenticated user can like a post', function () {
    Livewire::actingAs($this->user)
        ->test(\App\Http\Livewire\Blog\PostList::class)
        ->call('toggleLike', $this->post->id)
        ->assertSet("likesCount.{$this->post->id}", 1);
});

test('puede restaurar un post eliminado', function () {
    $post = Post::factory()->for($this->user)->create();
    $post->delete();

    Livewire::actingAs($this->user)
        ->test(PostList::class)
        ->call('restore', $post->id);

    $this->assertFalse(Post::onlyTrashed()->where('id', $post->id)->exists());
});


test('puede buscar posts por título', function () {
    Post::factory()->create(['title' => 'Pizza saludable']);
    Post::factory()->create(['title' => 'Sopa de verduras']);


    Livewire::test(PostList::class)
        ->set('search', 'Pizza')
        ->assertViewHas('posts', fn($posts) => $posts->contains('title', 'Pizza saludable'));

});

test('updatedMostrarFavoritos resetea paginación al activarse', function () {

    Livewire::test(PostList::class)
        ->set('page', 2)
        ->set('mostrarFavoritos', true)
        ->assertSet('page', 1);
});

test('updatingSearch resetea paginación al escribir', function () {
    Livewire::test(PostList::class)
        ->set('page', 2)
        ->set('search', 'test')
        ->assertSet('page', 1);
});

test('showTrashed muestra los posts eliminados', function () {
    $post = Post::factory()->for($this->user)->create();
    $post->delete();

    Livewire::test(PostList::class)
        ->set('showTrashed', true)
        ->assertSee($post->title);
});
