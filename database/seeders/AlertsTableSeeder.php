<?php

namespace Database\Seeders;

use App\Models\Aviso;
use App\Models\Zona;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar datos base
        DB::table('avisos')->insert([
            [
                'tipo' => 'puntual',
                'fecha_hora' => '2025-02-03 15:45:00',
                'categoria_id' => 2,
                'paciente_id' => 1,
                'operador_id' => 1,
                'tipo_aviso' => 'medicacion',
                'descripcion' => 'Recordatorio de medicación',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Crear avisos aleatorios adicionales
        Aviso::factory()
            ->count(10)
            ->create();
    }
}