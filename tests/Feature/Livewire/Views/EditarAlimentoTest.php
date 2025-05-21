<?php

use App\Http\Livewire\EditarAlimento;
use App\Models\User;
use App\Models\Dieta;
use App\Models\DietaAlimento;
use App\Models\Alimento;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the component', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $dieta = Dieta::factory()->create(['user_id' => $user->id]);
    $alimento = Alimento::factory()->create();
    $dietaAlimento = DietaAlimento::factory()->create([
        'dieta_id' => $dieta->id,
        'alimento_id' => $alimento->id,
        'dia' => 'Lunes',
        'tipo_comida' => 'Cena',
    ]);

    Livewire::test(EditarAlimento::class, [
        'dia' => 'Lunes',
        'tipoComida' => 'Cena',
        'alimentoId' => $alimento->id,
    ])->assertStatus(200);
});

it('updates food quantity', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $dieta = Dieta::factory()->create(['user_id' => $user->id]);
    $alimento = Alimento::factory()->create();
    $dietaAlimento = DietaAlimento::factory()->create([
        'dieta_id' => $dieta->id,
        'alimento_id' => $alimento->id,
        'dia' => 'Lunes',
        'tipo_comida' => 'Cena',
        'cantidad' => 100
    ]);

    Livewire::test(EditarAlimento::class, [
        'dia' => 'Lunes',
        'tipoComida' => 'Cena',
        'alimentoId' => $alimento->id,
    ])->set('cantidad', 150)
        ->call('actualizar');

    $this->assertDatabaseHas('dieta_alimentos', ['id' => $dietaAlimento->id, 'cantidad' => 150]);
});
