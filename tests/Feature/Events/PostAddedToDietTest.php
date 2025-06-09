<?php

use App\Models\User;
use App\Models\Post;
use App\Events\PostAddedToDiet;

test('PostAddedToDiet event carries correct user and post', function () {
    // Crear usuario manualmente
    $user = User::factory()->create();

    // Forzar al factory a usar ese user_id
    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);

    $event = new PostAddedToDiet($user, $post);

    expect($event->user->id)->toBe($user->id);
    expect($event->post->id)->toBe($post->id);
});

