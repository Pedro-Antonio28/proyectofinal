<?php

use App\Models\User;
use App\Models\Dieta;
use App\Models\Alimento;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear el rol `nutricionista` si no existe
    Role::factory()->create(['name' => 'nutricionista']);
});

it('allows a nutritionist to access the panel', function () {
    $nutricionista = User::factory()->create();

    // Obtener el rol nutricionista y asignarlo
    $role = Role::where('name', 'nutricionista')->first();
    $nutricionista->roles()->attach($role->id);

    $this->actingAs($nutricionista)
        ->get(route('nutricionista.panel'))
        ->assertStatus(200);
});

it('allows a nutritionist to view a client’s diet', function () {
    $nutricionista = User::factory()->create();
    $cliente = User::factory()->create();

    $role = Role::where('name', 'nutricionista')->first();
    $nutricionista->roles()->attach($role->id);
    $nutricionista->clientes()->attach($cliente);

    $dieta = Dieta::factory()->create(['user_id' => $cliente->id]);

    $this->actingAs($nutricionista)
        ->get(route('nutricionista.cliente.dieta', $cliente->id))
        ->assertStatus(200)
        ->assertViewHas('cliente')
        ->assertViewHas('dieta');
});

it('allows a nutritionist to add food to a client’s diet', function () {
    $nutricionista = User::factory()->create();
    $cliente = User::factory()->create();

    $role = Role::where('name', 'nutricionista')->first();
    $nutricionista->roles()->attach($role->id);
    $nutricionista->clientes()->attach($cliente);

    $dieta = Dieta::factory()->create(['user_id' => $cliente->id]);

    $data = [
        'nombre' => 'Pollo',
        'calorias' => 200,
        'proteinas' => 30,
        'carbohidratos' => 0,
        'grasas' => 5,
        'cantidad' => 100,
        'dia' => 'Lunes',
        'tipo_comida' => 'Almuerzo'
    ];

    $this->actingAs($nutricionista)
        ->post(route('nutricionista.dieta.add', $cliente->id), $data)
        ->assertRedirect(route('nutricionista.cliente.dieta', $cliente->id));

    $this->assertDatabaseHas('dietas', ['user_id' => $cliente->id]);
});



it('allows a nutritionist to delete a food item from a diet', function () {
    $nutricionista = User::factory()->create();
    $cliente = User::factory()->create();

    $role = Role::where('name', 'nutricionista')->first();
    $nutricionista->roles()->attach($role->id);
    $nutricionista->clientes()->attach($cliente);

    $dieta = Dieta::factory()->create(['user_id' => $cliente->id]);
    $alimento = Alimento::factory()->create();
    $dietaData = [
        'Lunes' => [
            'Almuerzo' => [
                [
                    'alimento_id' => $alimento->id,
                    'nombre' => 'Pollo',
                    'cantidad' => 100
                ]
            ]
        ]
    ];
    $dieta->update(['dieta' => json_encode($dietaData)]);

    $this->actingAs($nutricionista)
        ->delete(route('nutricionista.dieta.delete', [
            'clienteId' => $cliente->id,
            'dia' => 'Lunes',
            'tipoComida' => 'Almuerzo',
            'alimentoId' => $alimento->id
        ]))
        ->assertRedirect(route('nutricionista.cliente.dieta', $cliente->id));
});
