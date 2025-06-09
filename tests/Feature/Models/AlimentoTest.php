<?php

use App\Models\Alimento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('puede crear un alimento con campos válidos', function () {
    $alimento = Alimento::factory()->create([
        'nombre' => 'Avena',
        'categoria' => 'Cereal',
        'imagen' => 'avena.jpg',
        'calorias' => 350,
        'proteinas' => 12,
        'carbohidratos' => 60,
        'grasas' => 5,
    ]);

    expect($alimento->nombre)->toBe('Avena')
        ->and($alimento->categoria)->toBe('Cereal')
        ->and($alimento->imagen)->toBe('avena.jpg')
        ->and($alimento->calorias)->toBe(350)
        ->and($alimento->proteinas)->toBe(12)
        ->and($alimento->carbohidratos)->toBe(60)
        ->and($alimento->grasas)->toBe(5);
});

test('puede asociarse a múltiples usuarios', function () {
    $alimento = Alimento::factory()->create();
    $users = User::factory()->count(2)->create();

    $alimento->usuarios()->attach($users->pluck('id'));

    expect($alimento->usuarios)->toHaveCount(2)
        ->and($alimento->usuarios->first())->toBeInstanceOf(User::class);
});

test('fillable contiene solo los campos permitidos', function () {
    $alimento = new Alimento();
    $fillable = $alimento->getFillable();

    expect($fillable)->toBe([
        'nombre',
        'categoria',
        'imagen',
        'calorias',
        'proteinas',
        'carbohidratos',
        'grasas',
    ]);
});
