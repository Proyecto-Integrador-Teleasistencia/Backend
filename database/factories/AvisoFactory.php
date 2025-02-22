<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AvisoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha_hora' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'tipo' => fake()->randomElement(['puntual', 'periódico']),
            'categoria_id' => Categoria::factory(),
            'paciente_id' => Paciente::factory(),
            'operador_id' => User::factory()->state(['role' => 'operator']),
        ];
    }
}
