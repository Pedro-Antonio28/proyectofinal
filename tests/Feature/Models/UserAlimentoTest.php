<?php

use App\Models\User;
use App\Models\Alimento;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can associate a user with a favorite food', function () {
    $user = User::factory()->create();
    $food = Alimento::factory()->create();

    $user->alimentos()->attach($food);

    expect($user->alimentos)->toHaveCount(1);
    expect($user->alimentos->first()->id)->toBe($food->id);
});

it('can retrieve favorite foods of a user', function () {
    $user = User::factory()->create();
    $food = Alimento::factory()->create();

    $user->alimentos()->attach($food);

    $retrievedFoods = $user->alimentos()->get();

    expect($retrievedFoods)->toHaveCount(1);
    expect($retrievedFoods->first()->id)->toBe($food->id);
});
