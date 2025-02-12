<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'datetime' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'periodicity' => fake()->randomElement(['diaria', 'semanal', 'mensual', 'trimestral', 'semestral', 'anual']),
            'category_id' => Category::factory(),
            'patient_id' => Patient::factory(),
        ];
    }
}
