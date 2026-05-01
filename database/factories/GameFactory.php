<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->paragraph(),
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
            'min_players' => fake()->numberBetween(1, 2),
            'max_players' => fake()->numberBetween(3, 8),
            'spots' => fake()->numberBetween(2, 8),
            'duration' => fake()->numberBetween(30, 180),
            'status' => 'available',
            'category_id' => null,
            'price' => fake()->randomFloat(2, 10, 100),
            'image_url' => null,
        ];
    }
}
