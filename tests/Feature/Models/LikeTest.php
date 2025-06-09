<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('like belongs to a user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $like = Like::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    expect($like->user)->toBeInstanceOf(User::class)
        ->and($like->user->id)->toBe($user->id);
});

test('like belongs to a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $like = Like::create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    expect($like->post)->toBeInstanceOf(Post::class)
        ->and($like->post->id)->toBe($post->id);
});

test('like model has fillable attributes', function () {
    $like = new Like();

    expect($like->getFillable())->toBe(['user_id', 'post_id']);
});
