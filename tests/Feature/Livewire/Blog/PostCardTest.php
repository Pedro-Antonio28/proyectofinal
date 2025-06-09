<?php

use App\Http\Livewire\Blog\PostCard;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->post = Post::factory()->for($this->user)->create([
        'macros' => ['calories' => 100, 'protein' => 20, 'carbs' => 30, 'fat' => 10],
    ]);
});
test('post card renders correctly', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create([
        'macros' => ['calories' => 120, 'protein' => 10, 'carbs' => 20, 'fat' => 5]
    ]);

    Livewire::actingAs($user)
        ->test(PostCard::class, [
            'post' => $post,
            'mostrarNota' => false,
        ])
        ->assertSee($post->title)
        ->assertSee('120')
        ->assertSee('10')
        ->assertSee('20')
        ->assertSee('5')
        ->assertSee('❤️');
});

test('shows note if enabled and user has one', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    $user->postsWhoSavedIt()->attach($post->id, ['custom_notes' => 'nota de prueba']);

    Livewire::actingAs($user)
        ->test(PostCard::class, [
            'post' => $post,
            'mostrarNota' => true,
        ])
        ->assertSee('nota de prueba');
});

// ✅ Test para ver si el botón de eliminar aparece solo si el usuario es el dueño
test('delete button is visible only for owner', function () {
    Livewire::actingAs($this->user)
        ->test(PostCard::class, ['post' => $this->post])
        ->assertSee('Eliminar');

    $anotherUser = User::factory()->create();

    Livewire::actingAs($anotherUser)
        ->test(PostCard::class, ['post' => $this->post])
        ->assertDontSee('Eliminar');
});


test('no muestra botón eliminar si no es el autor', function () {
    $otro = User::factory()->create();

    Livewire::actingAs($otro)
        ->test(PostCard::class, ['post' => $this->post])
        ->assertDontSee('Eliminar');
});

test('renderiza correctamente el título y los macros', function () {
    Livewire::actingAs($this->user)
        ->test(PostCard::class, ['post' => $this->post])
        ->assertSee($this->post->title)
        ->assertSee((string) $this->post->macros['calories'])
        ->assertSee((string) $this->post->macros['protein']);
});


test('mount inicializa correctamente los datos del post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    Livewire::test(PostCard::class, ['post' => $post])
        ->assertSet('postId', $post->id)
        ->assertSet('title', $post->title)
        ->assertSet('image', $post->image_path)
        ->assertSet('images', $post->images->pluck('path')->toArray())
        ->assertSet('likes', $post->likes()->count())
        ->assertSet('postUserId', $post->user_id)
        ->assertSet('mostrarNota', false);
});

test('like añade y quita like correctamente', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(PostCard::class, ['post' => $post])
        ->call('like')
        ->assertSet('likes', 1);

    // Vuelve a dar like (debería quitarlo)
    Livewire::actingAs($user)
        ->test(PostCard::class, ['post' => $post])
        ->call('like')
        ->assertSet('likes', 0);
});

test('eliminarPost emite evento correctamente', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(PostCard::class, ['post' => $post])
        ->call('eliminarPost')
        ->assertDispatched('deletePost', $post->id);
});

test('render devuelve la vista correcta', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(PostCard::class, ['post' => $post])
        ->assertViewIs('livewire.blog.post-card');
});
