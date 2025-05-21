<?php

use App\Http\Requests\UpdateAlimentoRequest;
use Illuminate\Support\Facades\Validator;

it('validates update alimento request', function () {
    $request = new UpdateAlimentoRequest();

    $data = [
        'nombre' => 'Pechuga de Pollo',
        'calorias' => 130,
        'proteinas' => 27,
        'carbohidratos' => 1,
        'grasas' => 6,
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when proteinas is not a number', function () {
    $request = new UpdateAlimentoRequest();

    $data = [
        'nombre' => 'Pechuga de Pollo',
        'calorias' => 130,
        'proteinas' => 'invalid',
        'carbohidratos' => 1,
        'grasas' => 6,
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});
