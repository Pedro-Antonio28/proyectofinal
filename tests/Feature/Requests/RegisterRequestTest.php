<?php

use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;

it('validates register request', function () {
    $request = new RegisterRequest();

    $data = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when password confirmation does not match', function () {
    $request = new RegisterRequest();

    $data = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'wrongpassword',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});
