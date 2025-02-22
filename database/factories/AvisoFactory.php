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
            'categoria_id' => Categoria::inRandomOrder()->first()->id,
            'paciente_id' => Paciente::inRandomOrder()->first()->id,
            'operador_id' => User::where('role', 'operator')->inRandomOrder()->first()->id,
        ];
    }
}
