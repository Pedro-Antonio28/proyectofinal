<?php

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

beforeEach(function () {
    // Crear un usuario y autenticarlo
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('validates profile update request', function () {
    $request = new ProfileUpdateRequest();

    // Simular una solicitud con datos válidos
    $data = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
    ];

    // Simular una instancia de la request y definir el usuario autenticado
    $request->setUserResolver(fn () => $this->user);

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when name is missing in profile update request', function () {
    $request = new ProfileUpdateRequest();

    // Simular datos sin el campo 'name'
    $data = [
        'email' => 'john.doe@example.com',
    ];

    // Definir el usuario autenticado en la request
    $request->setUserResolver(fn () => $this->user);

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});
