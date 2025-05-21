<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dieta;
use App\Models\User;
use Carbon\Carbon;

class DietaFactory extends Factory
{
    protected $model = Dieta::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'semana' => Carbon::now()->weekOfYear,
            'dieta' => json_encode(['meals' => []]),
        ];
    }
}
