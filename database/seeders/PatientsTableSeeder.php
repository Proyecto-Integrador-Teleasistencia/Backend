<?php

namespace Database\Seeders;

use App\Models\Zona;
use App\Models\Paciente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pacientes')->insert([
            [
                'nombre' => 'Samuel Pastor',
                'fecha_nacimiento' => '1985-06-15',
                'direccion' => 'Calle Falsa 123',
                'ciudad' => 'Madrid',
                'codigo_postal' => '28001',
                'dni' => '12345678A',
                'tarjeta_sanitaria' => 'TS12345',
                'telefono' => '600123456',
                'email' => 'samuel.pastor@email.com',
                'zona_id' => 1,
                'situacion_personal' => 'Soltero',
                'estado_salud' => 'Buena salud',
                'condicion_vivienda' => 'Vive solo',
                'nivel_autonomia' => 'Independiente',
                'situacion_economica' => 'Estable'
            ],
            [
                'nombre' => 'Dani Cortés',
                'fecha_nacimiento' => '1992-09-23',
                'direccion' => 'Avenida Real 45',
                'ciudad' => 'Barcelona',
                'codigo_postal' => '08002',
                'dni' => '87654321B',
                'tarjeta_sanitaria' => 'TS67890',
                'telefono' => '610654321',
                'email' => 'dani.cortes@email.com',
                'zona_id' => 2,
                'situacion_personal' => 'Casado',
                'estado_salud' => 'Problemas respiratorios',
                'condicion_vivienda' => 'Vive con familia',
                'nivel_autonomia' => 'Parcialmente dependiente',
                'situacion_economica' => 'Bajos recursos'
            ]
        ]);

        Zona::all()->each(function ($zone) {
            Paciente::factory()
                ->count(fake()->numberBetween(3, 5))
                ->state([
                    'zona_id' => $zone->id,
                    'situacion_personal' => fake()->randomElement(['Soltero', 'Casado']),
                    'estado_salud' => fake()->randomElement(['Buena salud', 'Problemas respiratorios']),
                    'condicion_vivienda' => fake()->randomElement(['Vive solo', 'Vive con familia']),
                    'nivel_autonomia' => fake()->randomElement(['Independiente', 'Parcialmente dependiente']),
                    'situacion_economica' => fake()->randomElement(['Estable', 'Bajos recursos'])
                ])
                ->create();
        });
    }
}
