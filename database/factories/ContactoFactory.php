<?php

namespace Database\Factories;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactPerson>
 */
class ContactoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'nombre' => fake()->firstName(),
            'telefono' => fake()->phoneNumber(),
            'relacion' => fake()->randomElement(['Hijo/a', 'Hermano/a', 'Sobrino/a', 'Vecino/a', 'Amigo/a']),
        ];
    }
}
