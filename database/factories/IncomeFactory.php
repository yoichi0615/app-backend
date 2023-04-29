<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => fake()->randomNumber(),
            'amount' => fake()->numberBetween($min = 1, $max = 1000),
            'category_id' => fake()->randomNumber(),
            'date' => fake()->date(),
            'memo' => fake()->url()
        ];
    }
}
