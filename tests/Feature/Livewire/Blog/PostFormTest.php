<?php

use App\Http\Livewire\Blog\PostForm;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('renders post form correctly', function () {
    Livewire::test(PostForm::class)
        ->assertStatus(200)
        ->assertSee(__('messages.add_new_recipe'));
});

test('validates required fields', function () {
    Livewire::test(PostForm::class)
        ->call('guardarPost')
        ->assertHasErrors([
            'post.title' => 'required',
            'post.description' => 'required',
            'macrosData.calories' => 'required',
            'macrosData.protein' => 'required',
            'macrosData.carbs' => 'required',
            'macrosData.fat' => 'required',
        ]);
});

test('can add and remove ingredients', function () {
    Livewire::test(PostForm::class)
        ->assertCount('ingredients', 1) // ← ya hay uno
        ->call('addIngredient')
        ->assertCount('ingredients', 2)
        ->call('removeIngredient', 0)
        ->assertCount('ingredients', 1); // queda uno (el agregado o el inicial dependiendo del índice)
});


test('can save post with ingredients and macros', function () {
    $image = UploadedFile::fake()->image('receta.jpg');

    Livewire::test(PostForm::class)
        ->set('post.title', 'Tortilla de Avena')
        ->set('post.description', 'Una receta rica en proteínas')
        ->set('ingredients', [
            ['name' => 'Avena', 'quantity' => '50g'],
            ['name' => 'Huevos', 'quantity' => '2 unidades']
        ])
        ->set('macrosData', [
            'calories' => 350,
            'protein' => 20,
            'carbs' => 30,
            'fat' => 15
        ])
        ->set('images', [$image])
        ->call('guardarPost')
        ->assertHasNoErrors()
        ->assertSessionHas('success');
});
