<?php

use App\Http\Livewire\Blog\PostDetail;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create(['is_premium' => true]);
    $this->post = Post::factory()->create([
        'title' => 'Tortilla Fit',
        'macros' => [
            'calories' => 120,
            'protein' => 10,
            'carbs' => 2,
            'fat' => 5,
        ],
        'ingredients' => [
            ['name' => 'Huevos', 'quantity' => '2'],
            ['name' => 'Avena', 'quantity' => '30g'],
        ],
    ]);
    PostImage::factory()->create([
        'post_id' => $this->post->id,
        'path' => 'posts/example.jpg',
    ]);
});

test('renders post detail with correct data', function () {
    actingAs($this->user);

    Livewire::test(PostDetail::class, ['post' => $this->post])
        ->assertSee('Tortilla Fit')
        ->assertSee('Huevos')
        ->assertSee('Avena')
        ->assertSee('120')
        ->assertSee('10')
        ->assertSee('2')
        ->assertSee('5');
});

test('shows premium button if user is premium', function () {
    actingAs($this->user);

    Livewire::test(PostDetail::class, ['post' => $this->post])
        ->assertSee(__('messages.add_to_my_diet'));
});

test('shows modal when premium user clicks add button', function () {
    actingAs($this->user);

    Livewire::test(PostDetail::class, ['post' => $this->post])
        ->call('lanzarModalAñadir', $this->post->id)
        ->assertSet('mostrarModal', true);
});

test('non-premium user does not trigger modal logic', function () {
    $user = User::factory()->create(['is_premium' => false]);
    actingAs($user);

    Livewire::test(PostDetail::class, ['post' => $this->post])
        ->assertDontSee('wire:click="lanzarModalAñadir');
});

test('guardarPostEnDieta requiere campos', function () {
    $user = User::factory()->create(['is_premium' => true]);
    $this->actingAs($user);

    $post = Post::factory()->create([
        'macros' => ['calories' => 100, 'protein' => 10, 'carbs' => 20, 'fat' => 5],
    ]);

    Livewire::test(PostDetail::class, ['post' => $post])
        ->call('lanzarModalAñadir', $post->id)
        ->set('diaSeleccionado', '')
        ->set('tipoComidaSeleccionado', '')
        ->set('cantidadSeleccionada', '') // '' para que dispare "required"
        ->call('guardarPostEnDieta')
        ->assertHasErrors(['diaSeleccionado' => 'required'])
        ->assertHasErrors(['tipoComidaSeleccionado' => 'required'])
        ->assertHasErrors(['cantidadSeleccionada' => 'required']);
});

test('lanzarModalAñadir establece mostrarModal en true', function () {
    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create(['is_premium' => true]);

    Livewire::actingAs($user)
        ->test(\App\Http\Livewire\Blog\PostDetail::class, ['post' => $post])
        ->call('lanzarModalAñadir', $post->id)
        ->assertSet('mostrarModal', true)
        ->assertSet('cantidadSeleccionada', 100);
});

test('actualiza día y tipo de comida seleccionados', function () {
    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create(['is_premium' => true]);

    Livewire::actingAs($user)
        ->test(\App\Http\Livewire\Blog\PostDetail::class, ['post' => $post])
        ->set('diaSeleccionado', 'Lunes')
        ->set('tipoComidaSeleccionado', 'Desayuno')
        ->assertSet('diaSeleccionado', 'Lunes')
        ->assertSet('tipoComidaSeleccionado', 'Desayuno');
});


test('guardarPostEnDieta guarda el post en la dieta del usuario', function () {
    $post = \App\Models\Post::factory()->create([
        'macros' => ['calories' => 200, 'protein' => 10, 'carbs' => 20, 'fat' => 5],
    ]);

    $user = \App\Models\User::factory()->create(['is_premium' => true]);

    Livewire::actingAs($user)
        ->test(\App\Http\Livewire\Blog\PostDetail::class, ['post' => $post])
        ->set('postIdParaAñadir', $post->id) // <- necesario para findOrFail
        ->set('mostrarModal', true)
        ->set('diaSeleccionado', 'Lunes')
        ->set('tipoComidaSeleccionado', 'Comida')
        ->set('cantidadSeleccionada', 150)
        ->set('esFavorito', true)
        ->set('notaPersonal', 'Muy rica esta receta')
        ->call('guardarPostEnDieta')
        ->assertSet('mostrarModal', false);
});


test('valida que el día esté seleccionado antes de guardar', function () {
    $post = \App\Models\Post::factory()->create();

    $user = \App\Models\User::factory()->create(['is_premium' => true]);

    Livewire::actingAs($user)
        ->test(\App\Http\Livewire\Blog\PostDetail::class, ['post' => $post])
        ->set('diaSeleccionado', '')
        ->set('tipoComidaSeleccionado', 'Cena')
        ->set('cantidadSeleccionada', 100)
        ->call('guardarPostEnDieta')
        ->assertHasErrors(['diaSeleccionado' => 'required']);
});
