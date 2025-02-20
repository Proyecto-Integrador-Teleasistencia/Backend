<?php

namespace Database\Seeders;

use App\Models\Paciente;
use App\Models\Contacto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactPersonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar datos base
        DB::table('contactos')->insert([
            [
                'paciente_id' => 1,
                'nombre' => 'María Pastor',
                'telefono' => '612345678',
                'relacion' => 'Hermana',
            ],
            [
                'paciente_id' => 1,
                'nombre' => 'Pedro Pastor',
                'telefono' => '622345678',
                'relacion' => 'Padre',
            ],
            [
                'paciente_id' => 2,
                'nombre' => 'Laura Cortés',
                'telefono' => '632345678',
                'relacion' => 'Esposa',
            ]
        ]);

        // Crear contactos aleatorios adicionales (1-3 por paciente)
        Paciente::all()->each(function ($paciente) {
            Contacto::factory()
                ->count(fake()->numberBetween(1, 3))
                ->create(['paciente_id' => $paciente->id]);
        });
    }
}
