<?php

use App\Http\Requests\StoreAlimentoRequest;
use Illuminate\Support\Facades\Validator;


function getValidationErrors(array $data): array {
    $validator = Validator::make($data, (new StoreAlimentoRequest())->rules(), (new StoreAlimentoRequest())->messages());
    return $validator->errors()->toArray();
}
it('validates store alimento request', function () {
    $request = new StoreAlimentoRequest();

    $data = [
        'nombre' => 'Pollo',
        'calorias' => 120,
        'proteinas' => 25,
        'carbohidratos' => 0,
        'grasas' => 5,
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when nombre is missing in store alimento request', function () {
    $request = new StoreAlimentoRequest();

    $data = [
        'calorias' => 120,
        'proteinas' => 25,
        'carbohidratos' => 0,
        'grasas' => 5,
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});

it('valida que todos los campos sean requeridos', function () {
    $errors = getValidationErrors([]);

    expect($errors)->toHaveKeys([
        'nombre', 'calorias', 'proteinas', 'carbohidratos', 'grasas'
    ]);
});

it('muestra mensajes personalizados definidos en messages()', function () {
    $errors = getValidationErrors([]);

    expect($errors['nombre'][0])->toBe('El nombre del alimento es obligatorio.');
    expect($errors['calorias'][0])->toBe('Las calorías son obligatorias.');
    expect($errors['proteinas'][0])->toBe('Las proteínas son obligatorias.');
    expect($errors['carbohidratos'][0])->toBe('Los carbohidratos son obligatorios.');
    expect($errors['grasas'][0])->toBe('Las grasas son obligatorias.');
});

it('pasa validación con datos válidos sin imagen ni categoría', function () {
    $data = [
        'nombre' => 'Pollo',
        'calorias' => 100,
        'proteinas' => 20,
        'carbohidratos' => 10,
        'grasas' => 5,
        // imagen y categoria no son requeridos por reglas actuales
    ];

    $validator = Validator::make($data, (new StoreAlimentoRequest())->rules());
    expect($validator->fails())->toBeFalse();
});
