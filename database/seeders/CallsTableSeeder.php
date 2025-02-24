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

    public function run(): void
    {
        DB::table('llamadas')->insert([
            [
                'paciente_id' => 1,
                'operador_id' => 1,
                'tipo_llamada' => 'entrante',
                'duracion' => 180, 
                'estado' => 'completada',
                'motivo' => 'Emergencia médica',
                'descripcion' => 'El paciente reporta dolor en el pecho. Se ha contactado con servicios de emergencia.',
                'fecha_hora' => '2025-02-18 09:30:00',
                'categoria_id' => 4, 
                'subcategoria_id' => 8, 
                'fecha_completada' => '2025-02-18 09:35:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'paciente_id' => 2,
                'operador_id' => 2,
                'tipo_llamada' => 'saliente',
                'duracion' => 300, 
                'estado' => 'completada',
                'motivo' => 'Seguimiento rutinario',
                'descripcion' => 'Llamada de seguimiento mensual. El paciente se encuentra bien.',
                'fecha_hora' => '2025-02-18 10:15:00',
                'categoria_id' => 1, 
                'subcategoria_id' => 1,
                'fecha_completada' => '2025-02-18 10:20:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'paciente_id' => 1,
                'operador_id' => 1,
                'tipo_llamada' => 'entrante',
                'duracion' => 120,
                'estado' => 'completada',
                'motivo' => 'Consulta de información',
                'descripcion' => 'El paciente solicita información sobre horarios de atención.',
                'fecha_hora' => '2025-02-18 11:00:00',
                'categoria_id' => 5, 
                'subcategoria_id' => 12, 
                'fecha_completada' => '2025-02-18 11:05:00',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $pacientes = Paciente::all();
        $operadores = User::where('role', 'operator')->get();

        foreach ($pacientes as $paciente) {
            $numLlamadas = rand(1, 2);
            
            for ($i = 0; $i < $numLlamadas; $i++) {
                Llamada::factory()->create([
                    'paciente_id' => $paciente->id,
                    'operador_id' => $operadores->random()->id
                ]);
            }
        }
    }
}
