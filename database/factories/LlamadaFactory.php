<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Llamada>
 */
class LlamadaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo_llamada' => fake()->randomElement(['entrante', 'saliente']),
            'duracion' => fake()->numberBetween(60, 900), // Entre 1 y 15 minutos
            'estado' => fake()->randomElement(['completada', 'perdida', 'en_curso']),
            'motivo' => fake()->sentence(),
            'descripcion' => fake()->paragraph(),
            'fecha_hora' => fake()->dateTimeBetween('-1 month', 'now'),
            'categoria_id' => Categoria::factory(),
            'subcategoria_id' => Subcategoria::factory(),
        ];
    }
}
