<?php

namespace Database\Seeders;

use App\Models\Llamada;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar datos base
        DB::table('llamadas')->insert([
            [
                'paciente_id' => 1,
                'operador_id' => 1,
                'tipo_llamada' => 'entrante',
                'duracion' => 180, // 3 minutos
                'estado' => 'completada',
                'motivo' => 'Emergencia médica',
                'descripcion' => 'El paciente reporta dolor en el pecho. Se ha contactado con servicios de emergencia.',
                'fecha_hora' => '2025-02-18 09:30:00',
                'categoria_id' => 4, // Emergencias
                'subcategoria_id' => 8, // Emergencias sanitarias
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'paciente_id' => 2,
                'operador_id' => 2,
                'tipo_llamada' => 'saliente',
                'duracion' => 300, // 5 minutos
                'estado' => 'completada',
                'motivo' => 'Seguimiento rutinario',
                'descripcion' => 'Llamada de seguimiento mensual. El paciente se encuentra bien.',
                'fecha_hora' => '2025-02-18 10:15:00',
                'categoria_id' => 1, // Seguimiento
                'subcategoria_id' => 1, // Seguimiento rutinario
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'paciente_id' => 1,
                'operador_id' => 1,
                'tipo_llamada' => 'entrante',
                'duracion' => 120, // 2 minutos
                'estado' => 'completada',
                'motivo' => 'Consulta de información',
                'descripcion' => 'El paciente solicita información sobre horarios de atención.',
                'fecha_hora' => '2025-02-18 11:00:00',
                'categoria_id' => 5, // Comunicaciones no urgentes
                'subcategoria_id' => 12, // Petición de información
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Crear llamadas aleatorias adicionales
        $pacientes = Paciente::all();
        $operadores = User::where('role', 'operator')->get();

        foreach ($pacientes as $paciente) {
            // Generar entre 2 y 5 llamadas por paciente
            $numLlamadas = rand(1, 3);
            
            for ($i = 0; $i < $numLlamadas; $i++) {
                Llamada::factory()->create([
                    'paciente_id' => $paciente->id,
                    'operador_id' => $operadores->random()->id
                ]);
            }
        }
    }
}
