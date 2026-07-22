<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['cattle', 'goat', 'sheep', 'pig', 'poultry', 'rabbit', 'horse', 'other']);

        return [
            'identification_number' => strtoupper(Str::random(3)) . '-' . fake()->unique()->numberBetween(1000, 999999),
            'name' => fake()->firstName(),
            'type' => $type,
            'breed' => fake()->randomElement(['Friesian', 'Ankole', 'Boer', 'Merino', 'Landrace', 'other']),
            'gender' => fake()->randomElement(['male', 'female']),
            'age' => fake()->numberBetween(1, 120),
            'weight' => fake()->randomFloat(2, 5, 800),
            'health_status' => fake()->randomElement(['healthy', 'healthy', 'healthy', 'sick', 'treatment', 'recovering']),
            'date_bought' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'purchase_price' => fake()->randomFloat(2, 50000, 3000000),
            'notes' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
