<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactPerson>
 */
class ContactPersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'relationship' => fake()->randomElement(['Hijo/a', 'Hermano/a', 'Sobrino/a', 'Vecino/a', 'Amigo/a']),
            'address' => fake()->address(),
            'availability' => fake()->randomElement(['Mañanas', 'Tardes', 'Noches', '24h', 'Fines de semana']),
            'has_keys' => fake()->boolean(30), // 30% probabilidad de tener llaves
            'priority_level' => fake()->numberBetween(1, 5),
        ];
    }
}
