<?php

namespace Database\Factories;

use App\Models\Paciente;
use App\Models\Zona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'fecha_nacimiento' => fake()->dateTimeBetween('-90 years', '-60 years'),
            'direccion' => fake()->streetAddress(),
            'ciudad' => fake()->city(),
            'codigo_postal' => fake()->postcode(),
            'dni' => fake()->unique()->numerify('#########'),
            'tarjeta_sanitaria' => fake()->unique()->numerify('SIP########'),
            'telefono' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail() . '.paciente',
            // 'zona_id' => Zona::factory(),
            'zona_id' => fake()->randomElement(Zona::pluck('id')->toArray()),
            'situacion_personal' => fake()->optional()->paragraph(),
            'estado_salud' => fake()->optional()->paragraph(),
            'condicion_vivienda' => fake()->optional()->paragraph(),
            'nivel_autonomia' => fake()->optional()->randomElement(['Dependiente', 'Semi-dependiente', 'Autónomo']),
            'situacion_economica' => fake()->optional()->paragraph(),
        ];
    }
}
