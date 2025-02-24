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
            'codigo_postal' => fake()->regexify('^\d{5}$'),
            'dni' => fake()->regexify('^[0-9]{8}[A-Z]$'),
            'tarjeta_sanitaria' => fake()->unique()->numerify('SIP########'),
            'telefono' => fake()->regexify('^(\+34|0034|34)?[6789]\d{8}$'),
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
