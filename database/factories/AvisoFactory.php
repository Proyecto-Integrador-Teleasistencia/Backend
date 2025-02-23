<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvisoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fecha_hora' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'tipo' => fake()->randomElement(['puntual', 'periodico']),
            'tipo_aviso' => fake()->randomElement(['medicacion', 'especial', 'seguimiento_emergencia', 'seguimiento_dol', 'seguimiento_alta', 'ausencia_temporal', 'retorno', 'preventivo']),
            'categoria_id' => Categoria::inRandomOrder()->first()->id,
            'paciente_id' => Paciente::inRandomOrder()->first()->id,
            'operador_id' => User::where('role', 'operator')->inRandomOrder()->first()->id,
            'descripcion' => fake()->sentence(),
        ];
    }
}
