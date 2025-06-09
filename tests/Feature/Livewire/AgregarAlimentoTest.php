<?php

use App\Http\Livewire\AgregarAlimento;
use App\Models\User;
use App\Models\Dieta;
use App\Models\Alimento;
use App\Models\DietaAlimento;
use function Pest\Laravel\actingAs;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('agregar alimento page renders correctly', function () {
    Livewire::withQueryParams(['dia' => 'Lunes', 'tipoComida' => 'Desayuno'])
        ->test(AgregarAlimento::class, ['dia' => 'Lunes', 'tipoComida' => 'Desayuno'])
        ->assertStatus(200)
        ->assertSee('Agregar Alimento'); // Ajustado al texto real de la vista
});

test('can add new alimento to dieta and dieta_alimento', function () {
    Livewire::test(AgregarAlimento::class, [
        'dia' => 'Lunes',
        'tipoComida' => 'Desayuno'
    ])
        ->set('nombre', 'Pollo a la plancha')
        ->set('calorias', 150)
        ->set('proteinas', 30)
        ->set('carbohidratos', 0)
        ->set('grasas', 3)
        ->call('guardar')
        ->assertRedirect(route('dashboard'));

    $alimento = Alimento::where('nombre', 'Pollo a la plancha')->first();
    $dieta = Dieta::where('user_id', $this->user->id)->first();

    expect($alimento)->not->toBeNull();
    expect($dieta)->not->toBeNull();
    expect(DietaAlimento::where('dieta_id', $dieta->id)->where('alimento_id', $alimento->id)->exists())->toBeTrue();

    $json = json_decode($dieta->fresh()->dieta, true);
    expect($json['Lunes']['Desayuno'][0]['nombre'])->toBe('Pollo a la plancha');
});

test('shows error when required fields are missing', function () {
    Livewire::test(AgregarAlimento::class, [
        'dia' => 'Martes',
        'tipoComida' => 'Cena'
    ])
        ->call('guardar')
        ->assertSee('❌ Verifica los campos'); // ✅ Compatible con tu mensaje flash personalizado
});
