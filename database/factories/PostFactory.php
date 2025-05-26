<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        return [
            'user_id' => User::whereHas('roles', fn ($q) => $q->where('name', 'usuario'))->inRandomOrder()->first()->id,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => $this->faker->paragraphs(3, true),
            'macros' => [
                'calories' => $this->faker->numberBetween(300, 900),
                'protein' => $this->faker->randomFloat(1, 10, 50),
                'carbs'   => $this->faker->randomFloat(1, 20, 100),
                'fat'     => $this->faker->randomFloat(1, 5, 30),
            ],

            'ingredients' => [
                ['name' => 'Pechuga de pollo', 'quantity' => '150g'],
                ['name' => 'Aceite de oliva', 'quantity' => '1 cucharada'],
                ['name' => 'Sal', 'quantity' => 'al gusto'],
            ],


            'image_path' => null,
        ];
    }
}
