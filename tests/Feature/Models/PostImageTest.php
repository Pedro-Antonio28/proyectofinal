<?php

use App\Models\PostImage;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('crea una PostImage correctamente con datos válidos', function () {
    $post = Post::factory()->create();

    $image = PostImage::create([
        'post_id' => $post->id,
        'path' => 'imagenes/post1.jpg',
    ]);

    expect($image)->toBeInstanceOf(PostImage::class)
        ->and($image->post_id)->toBe($post->id)
        ->and($image->path)->toBe('imagenes/post1.jpg');
});

it('tiene relación belongsTo con Post', function () {
    $post = Post::factory()->create();
    $image = PostImage::factory()->create(['post_id' => $post->id]);

    expect($image->post)->toBeInstanceOf(Post::class)
        ->and($image->post->id)->toBe($post->id);
});

it('lanza error si falta post_id al crear una PostImage', function () {
    expect(function () {
        PostImage::create([
            'path' => 'imagenes/post2.jpg',
        ]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

it('lanza error si falta path al crear una PostImage', function () {
    $post = Post::factory()->create();

    expect(function () use ($post) {
        PostImage::create([
            'post_id' => $post->id,
        ]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});
