<?php

use App\Http\Livewire\UserAlimentos;
use App\Models\Alimento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

// ✅ Se renderiza correctamente
test('el componente se renderiza correctamente', function () {
    $user = User::factory()->create();
    Livewire::actingAs($user)
        ->test(UserAlimentos::class)
        ->assertStatus(200);
});

// ✅ Carga favoritos correctamente en el mount
test('se cargan alimentos favoritos del usuario al inicializar', function () {
    $user = User::factory()->create();
    $alimento = Alimento::factory()->create();
    $user->alimentosFavoritos()->attach($alimento);

    Livewire::actingAs($user)
        ->test(UserAlimentos::class)
        ->assertSet('favoritos', [$alimento->id]);
});

// ✅ Guarda correctamente si cumple condiciones
test('puede guardar alimentos favoritos correctamente', function () {
    $user = User::factory()->create();

    // Crear alimentos de cada categoría con los nombres que tu lógica espera
    $categorias = [
        'proteina' => 6,
        'carbohidrato' => 4,
        'verdura' => 3,
        'fruta' => 3,
        'grasa' => 2,
    ];

    $ids = collect();
    foreach ($categorias as $categoria => $cantidad) {
        Alimento::factory($cantidad)->create(['categoria' => $categoria])
            ->each(fn($a) => $ids->push($a->id));
    }

    Livewire::actingAs($user)
        ->test(\App\Http\Livewire\UserAlimentos::class)
        ->set('favoritos', $ids->toArray())
        ->call('guardarSeleccion')
        ->assertSessionHas('message', 'Alimentos guardados correctamente.');
});


// ✅ Muestra errores si no se cumple el mínimo requerido por categoría
test('muestra error si no cumple con los alimentos mínimos por categoría', function () {
    $user = User::factory()->create();

    $alimentos = collect([
        ['categoria' => 'proteina'],
        ['categoria' => 'proteina'],
        ['categoria' => 'carbohidrato'],
        ['categoria' => 'verdura'],
    ])->map(fn($attrs) => Alimento::factory()->create($attrs));

    Livewire::actingAs($user)
        ->test(UserAlimentos::class)
        ->set('favoritos', $alimentos->pluck('id')->toArray())
        ->call('guardarSeleccion')
        ->assertHasErrors(['seleccion']);
});


