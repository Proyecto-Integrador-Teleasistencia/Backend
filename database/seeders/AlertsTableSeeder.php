<?php

namespace Database\Seeders;

use App\Models\Aviso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar datos base
        DB::table('avisos')->insert([
            [
                'tipo' => 'puntual',
                'fecha_hora' => '2025-02-03 15:45:00',
                'categoria_id' => 2,
                'paciente_id' => 1
            ]
        ]);

        // Crear alertas aleatorias adicionales
        Aviso::factory()
            ->count(10)
            ->create();
    }
}
