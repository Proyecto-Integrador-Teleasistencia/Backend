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
            'apellido' => fake()->lastName(),
            'telefono' => fake()->phoneNumber(),
            'relacion' => fake()->randomElement(['Hijo/a', 'Hermano/a', 'Sobrino/a', 'Vecino/a', 'Amigo/a']),
            'direccion' => fake()->address(),
            'disponibilidad' => fake()->randomElement(['Mañanas', 'Tardes', 'Noches', '24h', 'Fines de semana']),
            'tiene_llaves' => fake()->boolean(30), // 30% probabilidad de tener llaves
            'nivel_prioridad' => fake()->numberBetween(1, 5),
        ];
    }
}
