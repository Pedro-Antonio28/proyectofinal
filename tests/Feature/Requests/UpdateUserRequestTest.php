<?php

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;

test('email is required', function () {
    $data = ['email' => '', 'password' => null];
    $rules = (new UpdateUserRequest())->rules();

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
});

test('email must be valid', function () {
    $data = ['email' => 'not-an-email', 'password' => null];
    $rules = (new UpdateUserRequest())->rules();

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
});

test('password is optional', function () {
    $data = ['email' => 'valid@example.com'];
    $rules = (new UpdateUserRequest())->rules();

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeFalse();
});

test('password must be at least 6 characters if provided', function () {
    $data = ['email' => 'valid@example.com', 'password' => '123'];
    $rules = (new UpdateUserRequest())->rules();

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('password'))->toBeTrue();
});

test('password must be confirmed if provided', function () {
    $data = ['email' => 'valid@example.com', 'password' => '123456', 'password_confirmation' => '654321'];
    $rules = (new UpdateUserRequest())->rules();

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('password'))->toBeTrue();
});
