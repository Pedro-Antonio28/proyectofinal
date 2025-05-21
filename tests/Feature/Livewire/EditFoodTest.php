<?php

use App\Http\Livewire\EditarAlimento;
use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render the Edit Food component', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $dieta = Dieta::factory()->create(['user_id' => $user->id]);
    $alimento = Alimento::factory()->create();
    $dietaAlimento = DietaAlimento::factory()->create([
        'dieta_id' => $dieta->id,
        'alimento_id' => $alimento->id,
        'dia' => 1,
        'tipo_comida' => 'desayuno',
        'cantidad' => 100,
    ]);

    Livewire::test(EditarAlimento::class, [
        'dia' => 1,
        'tipoComida' => 'desayuno',
        'alimentoId' => $alimento->id,
    ])->assertStatus(200);
});

it('can update food quantity', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $dieta = Dieta::factory()->create(['user_id' => $user->id]);
    $alimento = Alimento::factory()->create();
    $dietaAlimento = DietaAlimento::factory()->create([
        'dieta_id' => $dieta->id,
        'alimento_id' => $alimento->id,
        'dia' => 1,
        'tipo_comida' => 'desayuno',
        'cantidad' => 100,
    ]);

    Livewire::test(EditarAlimento::class, [
        'dia' => 1,
        'tipoComida' => 'desayuno',
        'alimentoId' => $alimento->id,
    ])
        ->set('cantidad', 200)
        ->call('actualizar')
        ->assertSessionHas('message', '✅ Alimento actualizado con éxito.');

    $dietaAlimento->refresh();
    expect($dietaAlimento->cantidad)->toBe(200);
});
