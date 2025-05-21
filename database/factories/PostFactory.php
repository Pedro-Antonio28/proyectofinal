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
            ],
            'image_path' => null,
        ];
    }
}
