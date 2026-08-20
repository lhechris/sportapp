<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Equipe'.fake()->randomNumber(2,false),
            'whatsapp' => fake()->numerify('https://fakewhatsapp.com/#######'),
            'msg_convocation' => fake()->paragraph()
        ];
    }
}
