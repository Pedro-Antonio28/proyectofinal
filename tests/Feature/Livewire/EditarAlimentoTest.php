<?php

use App\Http\Livewire\EditarAlimento;
use App\Models\{User, Dieta, Alimento, DietaAlimento};
use function Pest\Laravel\actingAs;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);

    $this->alimento = Alimento::factory()->create([
        'nombre' => 'Tortilla',
        'calorias' => 200,
        'proteinas' => 10,
        'carbohidratos' => 15,
        'grasas' => 12,
    ]);

    $this->dieta = Dieta::factory()->create([
        'user_id' => $this->user->id,
        'dieta' => json_encode([
            'Lunes' => [
                'Cena' => [
                    [
                        'alimento_id' => $this->alimento->id,
                        'nombre' => $this->alimento->nombre,
                        'calorias' => 200,
                        'proteinas' => 10,
                        'carbohidratos' => 15,
                        'grasas' => 12,
                        'cantidad' => 100,
                        'consumido' => false,
                    ]
                ]
            ]
        ])
    ]);

    $this->dietaAlimento = DietaAlimento::factory()->create([
        'dieta_id' => $this->dieta->id,
        'alimento_id' => $this->alimento->id,
        'dia' => 'Lunes',
        'tipo_comida' => 'Cena',
        'cantidad' => 100,
        'consumido' => false,
    ]);
});

test('editar alimento page renders correctly', function () {
    Livewire::test(EditarAlimento::class, [
        'dia' => 'Lunes',
        'tipoComida' => 'Cena',
        'alimentoId' => $this->alimento->id,
    ])
        ->assertStatus(200)
        ->assertSee('Editar Alimento'); // más estable que buscar botones
});


test('can update alimento cantidad', function () {
    Livewire::test(EditarAlimento::class, [
        'dia' => 'Lunes',
        'tipoComida' => 'Cena',
        'alimentoId' => $this->alimento->id,
    ])
        ->set('cantidad', 250)
        ->call('actualizar')
        ->assertRedirect(route('dashboard'));

    expect($this->dietaAlimento->fresh()->cantidad)->toBe(250);

    $json = json_decode($this->dieta->fresh()->dieta, true);
    expect($json['Lunes']['Cena'][0]['cantidad'])->toBe(250);
});

test('shows error if cantidad is invalid', function () {
    Livewire::test(EditarAlimento::class, [
        'dia' => 'Lunes',
        'tipoComida' => 'Cena',
        'alimentoId' => $this->alimento->id,
    ])
        ->set('cantidad', 0)
        ->call('actualizar')
        ->assertSee('❌ Verifica los campos');
});

test('can delete alimento', function () {
    Livewire::test(EditarAlimento::class, [
        'dia' => 'Lunes',
        'tipoComida' => 'Cena',
        'alimentoId' => $this->alimento->id,
    ])
        ->call('eliminar')
        ->assertRedirect(route('dashboard'));

    expect(DietaAlimento::find($this->dietaAlimento->id))->toBeNull();
});
