<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Alimento;

class AlimentoFactory extends Factory
{
    protected $model = Alimento::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'categoria' => 'Frutas',
            'imagen' => 'default.jpg',
            'calorias' => $this->faker->numberBetween(50, 500),
            'proteinas' => $this->faker->randomFloat(2, 0, 50),
            'carbohidratos' => $this->faker->randomFloat(2, 0, 100),
            'grasas' => $this->faker->randomFloat(2, 0, 50),
        ];
    }
}
