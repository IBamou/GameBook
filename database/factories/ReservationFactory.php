<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'table_id' => \App\Models\Table::factory(),
            'game_id' => null,
            'date' => fake()->dateTimeBetween('now', '+1 month'),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
            'spots' => fake()->numberBetween(1, 4),
            'status' => 'pending',
            'price' => fake()->randomFloat(2, 20, 100),
        ];
    }
}
