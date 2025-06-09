<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Dieta;
use Illuminate\Support\Str;
use function Pest\Laravel\{get, post, put, delete, actingAs};
use App\Http\Requests\StoreAlimentoRequestNutricionista;
use Illuminate\Support\Facades\Validator;


beforeEach(function () {
    $this->nutricionista = User::factory()->create();
    $nutriRole = Role::firstOrCreate(['name' => 'nutricionista']);
    $this->nutricionista->roles()->attach($nutriRole);
    actingAs($this->nutricionista);

    $this->cliente = User::factory()->create();
    $this->nutricionista->clientes()->attach($this->cliente);
});

function makeValidator(array $data)
{
    $request = new StoreAlimentoRequestNutricionista();
    return Validator::make($data, $request->rules());
}

test('nutricionista can access panel', function () {
    $response = get(route('nutricionista.panel'));

    $response->assertOk();
    $response->assertSeeText('👥');
});

test('nutricionista can view client diet', function () {
    Dieta::factory()->create([
        'user_id' => $this->cliente->id,
        'dieta' => json_encode(['Lunes' => []]),
    ]);

    $response = get(route('nutricionista.cliente.dieta', $this->cliente->id));

    $response->assertOk();
    $response->assertSee($this->cliente->name);
});

test('nutricionista can add food to client diet', function () {
    $dieta = Dieta::factory()->create([
        'user_id' => $this->cliente->id,
        'dieta' => json_encode([]),
    ]);

    $data = [
        'nombre' => 'Manzana',
        'calorias' => 52,
        'proteinas' => 2,
        'carbohidratos' => 14,
        'grasas' => 1,
        'cantidad' => 150,
        'dia' => 'Lunes',
        'tipo_comida' => 'Desayuno',
    ];

    $response = post(route('nutricionista.dieta.add', $this->cliente->id), $data);

    $response->assertRedirect(route('nutricionista.cliente.dieta', $this->cliente->id));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('dietas', [
        'id' => $dieta->id,
    ]);
});

test('nutricionista can delete food from client diet', function () {
    $dieta = Dieta::factory()->create([
        'user_id' => $this->cliente->id,
        'dieta' => json_encode([
            'Lunes' => [
                'Comida' => [
                    [
                        'alimento_id' => 1,
                        'nombre' => 'Arroz',
                        'cantidad' => 100,
                        'calorias' => 130,
                        'proteinas' => 2,
                        'carbohidratos' => 28,
                        'grasas' => 1,
                    ]
                ]
            ]
        ]),
    ]);

    $response = delete(route('nutricionista.dieta.delete', [
        'clienteId' => $this->cliente->id,
        'dia' => 'Lunes',
        'tipoComida' => 'Comida',
        'alimentoId' => 1,
    ]));

    $response->assertRedirect(route('nutricionista.cliente.dieta', $this->cliente->id));
    $response->assertSessionHas('success');
});

test('validates with correct data', function () {
    $data = [
        'nombre' => 'Manzana',
        'calorias' => 52,
        'proteinas' => 1,
        'carbohidratos' => 14,
        'grasas' => 0,
        'cantidad' => 150,
        'dia' => 'Lunes',
        'tipo_comida' => 'Desayuno',
    ];

    $validator = makeValidator($data);
    expect($validator->passes())->toBeTrue();
});

test('fails when required fields are missing', function () {
    $validator = makeValidator([]);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->keys())->toContain('nombre', 'calorias', 'proteinas', 'carbohidratos', 'grasas', 'cantidad', 'dia', 'tipo_comida');
});

test('fails when numeric fields are below minimum', function () {
    $data = [
        'nombre' => 'Test',
        'calorias' => -1,
        'proteinas' => -1,
        'carbohidratos' => -1,
        'grasas' => -1,
        'cantidad' => 0,
        'dia' => 'Lunes',
        'tipo_comida' => 'Comida',
    ];

    $validator = makeValidator($data);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('calorias'))->toBeTrue();
    expect($validator->errors()->has('cantidad'))->toBeTrue();
});

test('fails when dia or tipo_comida are invalid', function () {
    $data = [
        'nombre' => 'Test',
        'calorias' => 100,
        'proteinas' => 10,
        'carbohidratos' => 10,
        'grasas' => 5,
        'cantidad' => 100,
        'dia' => 'NotADay',
        'tipo_comida' => 'Brunch',
    ];

    $validator = makeValidator($data);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('dia'))->toBeTrue();
    expect($validator->errors()->has('tipo_comida'))->toBeTrue();
});

test('authorize method returns true', function () {
    $request = new StoreAlimentoRequestNutricionista();
    expect($request->authorize())->toBeTrue();
});
