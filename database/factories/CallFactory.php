<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Call>
 */
class CallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'datetime' => fake()->dateTimeBetween('-6 months', 'now'),
            'description' => fake()->optional()->sentence(),
            'type' => fake()->randomElement(['outgoing', 'incoming']),
            'scheduled' => fake()->boolean(20), // 20% probabilidad de ser programada
            'operator_id' => User::factory()->state(['role' => 'operator']),
            'patient_id' => Patient::factory(),
            'category_id' => Category::factory(),
            'alert_id' => fake()->optional(0.3)->randomElement([Alert::factory()]), // 30% probabilidad de tener alerta
        ];
    }
}
