<?php

use App\Http\Requests\StoreAlimentoRequest;
use Illuminate\Support\Facades\Validator;

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
