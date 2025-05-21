<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DietaAlimento;
use App\Models\Dieta;
use App\Models\Alimento;

class DietaAlimentoFactory extends Factory
{
    protected $model = DietaAlimento::class;

    public function definition()
    {
        return [
            'dieta_id' => Dieta::factory(),
            'alimento_id' => Alimento::factory(),
            'dia' => $this->faker->numberBetween(1, 7),
            'tipo_comida' => 'desayuno',
            'cantidad' => $this->faker->numberBetween(50, 200),
            'consumido' => $this->faker->boolean,
        ];
    }
}
