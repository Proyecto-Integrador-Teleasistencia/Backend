<?php

namespace Database\Seeders;

use App\Models\Paciente;
use App\Models\ContactPerson;
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
                'apellido' => 'Fernández',
                'telefono' => '612345678',
                'parentesco' => 'Hermana',
                'direccion' => 'Calle Principal 123',
                'disponibilidad' => 'Mañanas y tardes',
                'tiene_llaves' => true,
                'nivel_prioridad' => 1
            ],
            [
                'paciente_id' => 1,
                'nombre' => 'Pedro Pastor',
                'apellido' => 'Gómez',
                'telefono' => '622345678',
                'parentesco' => 'Padre',
                'direccion' => 'Avenida Central 45',
                'disponibilidad' => 'Tardes',
                'tiene_llaves' => false,
                'nivel_prioridad' => 2
            ],
            [
                'paciente_id' => 2,
                'nombre' => 'Laura Cortés',
                'apellido' => 'Martínez',
                'telefono' => '632345678',
                'parentesco' => 'Esposa',
                'direccion' => 'Plaza Mayor 78',
                'disponibilidad' => '24h',
                'tiene_llaves' => true,
                'nivel_prioridad' => 1
            ]
        ]);

        // Crear contactos aleatorios adicionales (1-3 por paciente)
        Paciente::all()->each(function ($paciente) {
            ContactPerson::factory()
                ->count(fake()->numberBetween(1, 3))
                ->create(['paciente_id' => $paciente->id]);
        });
    }
}
