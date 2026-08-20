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
            'titre' => fake()->numerify('Match ####'),
            'date' => fake()->dateTimeBetween('now','+3 month')->format('Y-m-d H:i:s'),
            'numero' => fake()->numerify('####'),
        ];
    }
}
