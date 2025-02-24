<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Aviso;
use Illuminate\Database\Eloquent\Factories\Factory;

class LlamadaFactory extends Factory
{
    public function definition(): array
    {
        $planificada = fake()->boolean(80);

        $paciente = Paciente::inRandomOrder()->first();
        $categoria = Categoria::inRandomOrder()->first();
        $subcategoria = Subcategoria::where('categoria_id', $categoria->id)->inRandomOrder()->first();
        $operador = User::where('role', 'operator')->inRandomOrder()->first();

        $fecha_llamada = fake()->dateTimeBetween('-6 months', 'now');

        $aviso_id = null;
        if ($planificada) {
            $aviso = Aviso::create([
                'fecha_hora' => $fecha_llamada,
                'tipo' => fake()->randomElement(['puntual', 'periodico']),
                'tipo_aviso' => fake()->randomElement(['medicacion', 'especial', 'seguimiento_emergencia', 'seguimiento_dol', 'seguimiento_alta', 'ausencia_temporal', 'retorno', 'preventivo']),
                'categoria_id' => $categoria->id,
                'paciente_id' => $paciente->id,
                'operador_id' => $operador->id,
                'zona_id' => $paciente->zona_id,
                'descripcion' => fake()->sentence(),
            ]);
            $aviso_id = $aviso->id;
        }

        return [
            'fecha_hora' => $fecha_llamada,
            'tipo_llamada' => fake()->randomElement(['entrante', 'saliente']),
            'tipo_llamada_detalle' => fake()->randomElement(['Emergencia social', 'Emergencia sanitaria', 'Seguimiento rutinario', 'Consulta general']),
            'duracion' => fake()->numberBetween(60, 600),
            'estado' => fake()->randomElement(['completada', 'perdida', 'en_curso']),
            'motivo' => fake()->sentence(),
            'descripcion' => fake()->optional()->paragraph(),
            'planificada' => $planificada,
            'fecha_completada' => $planificada ? $fecha_llamada : null,
            'operador_id' => $operador->id,
            'paciente_id' => $paciente->id,
            'categoria_id' => $categoria->id,
            'subcategoria_id' => $subcategoria->id ?? null,
            'aviso_id' => $aviso_id,
        ];
    }
}
