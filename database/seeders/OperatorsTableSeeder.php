<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zone;
use App\Models\Operator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear operadores
        $admin = User::create([
            'nombre' => 'Samuel Carbonell',
            'email' => 'samuel.carbonell@email.com',
            'password' => Hash::make('1234'),
            'telefono' => '700123456',
            'zona_id' => 1,
            'fecha_contratacion' => '2023-01-10',
            'fecha_baja' => null
        ]);

        $operator = User::create([
            'nombre' => 'Carlos Perez',
            'email' => 'carlosperezalzina@gmail.com',
            'password' => Hash::make('1234'),
            'telefono' => '710654321',
            'zona_id' => 2,
            'fecha_contratacion' => '2023-02-15',
            'fecha_baja' => null
        ]);

        // Asignar zonas a los operadores
        $admin->zonas()->attach([1]);
        $operator->zonas()->attach([2]);
    }
}
