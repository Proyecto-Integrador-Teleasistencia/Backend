<?php

namespace Database\Seeders;

use App\Models\Incidencia;
use App\Models\Paciente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncidenciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los pacientes
        $pacientes = Paciente::all();

        foreach ($pacientes as $paciente) {
            // Crear una incidencia por paciente
            Incidencia::create([
                'paciente_id' => $paciente->id,
                'descripcion' => rand(0, 1) ? null : 'Incidència del pacient ' . $paciente->nombre
            ]);
        }
    }
}
