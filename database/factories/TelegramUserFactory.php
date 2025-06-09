<?php

namespace Database\Factories;

use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramUserFactory extends Factory
{
    protected $model = TelegramUser::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // o puedes pasar un ID concreto en los tests
            'telegram_id' => $this->faker->numerify('##########'),
        ];
    }
}

