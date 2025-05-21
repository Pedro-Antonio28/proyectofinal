<?php

use App\Models\DietaAlimento;
use App\Models\Dieta;
use App\Models\Alimento;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can_create_a_diet_food_relation', function () {
    $diet = Dieta::factory()->create();
    $food = Alimento::factory()->create();

    $dietFood = DietaAlimento::create([
        'dieta_id' => $diet->id,
        'alimento_id' => $food->id,
        'dia' => 1,
        'tipo_comida' => 'breakfast',
        'cantidad' => 100,
        'consumido' => false,
    ]);

    expect($dietFood)->toBeInstanceOf(DietaAlimento::class);
    expect($dietFood->dieta_id)->toBe($diet->id);
});

it('can_filter_consumed_foods_in_a_diet', function () {
    $diet = Dieta::factory()->create();
    $food = Alimento::factory()->create();

    DietaAlimento::factory()->create([
        'dieta_id' => $diet->id,
        'alimento_id' => $food->id,
        'dia' => 1,
        'consumido' => true,
    ]);

    $consumedFoods = DietaAlimento::consumidos($diet->id, 1)->get();

    expect($consumedFoods)->toHaveCount(1);
});
