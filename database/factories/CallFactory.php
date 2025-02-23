<?php

namespace Database\Factories;

use App\Models\Paciente;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Aviso;
use Illuminate\Database\Eloquent\Factories\Factory;

class CallFactory extends Factory
{
    public function definition(): array
    {
        $planificada = fake()->boolean(80); // 80% probabilidad de ser planificada

        $paciente = Paciente::inRandomOrder()->first();
        $categoria = Categoria::inRandomOrder()->first();
        $subcategoria = Subcategoria::inRandomOrder()->first();
        $operador = User::where('role', 'operator')->inRandomOrder()->first();

        if ($planificada) {
            $aviso = Aviso::create([
                'fecha_hora' => fake()->dateTimeBetween('-6 months', 'now'),
                'tipo' => fake()->randomElement(['puntual', 'periodico']),
                'tipo_aviso' => fake()->randomElement(['medicacion', 'especial', 'seguimiento_emergencia', 'seguimiento_dol', 'seguimiento_alta', 'ausencia_temporal', 'retorno', 'preventivo']),
                'categoria_id' => 1,
                'subcategoria_id' => 1,
                'paciente_id' => $paciente->id,
                'operador_id' => $operador->id,
                'descripcion' => fake()->sentence(),
            ]);
        }

        return [
            'fecha_hora' => fake()->dateTimeBetween('-6 months', 'now'),
            'descripcion' => fake()->optional()->sentence(),
            'tipo_llamada' => fake()->randomElement(['entrante', 'saliente']),
            'tipo_llamada_detalle' => fake()->randomElement(['Emergencia social', 'Emergencia sanitaria', 'Seguimiento rutinario', 'Consulta general']),
            'duracion' => fake()->numberBetween(60, 600),
            'estado' => fake()->randomElement(['completada', 'perdida', 'en_curso']),
            'planificada' => $planificada,
            'operador_id' => $operador->id,
            'paciente_id' => $paciente->id,
            'categoria_id' => 1,
            'subcategoria_id' => 1,
        ];


    }
}
