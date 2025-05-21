<?php

use App\Models\Alimento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can_create_a_food', function () {
    $food = Alimento::create([
        'nombre' => 'Apple',
        'categoria' => 'Fruits',
        'imagen' => 'apple.jpg',
        'calorias' => 52,
        'proteinas' => 0.3,
        'carbohidratos' => 14,
        'grasas' => 0.2,
    ]);

    expect($food)->toBeInstanceOf(Alimento::class);
    expect($food->nombre)->toBe('Apple');
});

it('can_associate_food_with_users', function () {
    $user = User::factory()->create();
    $food = Alimento::factory()->create();

    $user->alimentos()->attach($food);

    expect($user->alimentos()->count())->toBe(1);
});
