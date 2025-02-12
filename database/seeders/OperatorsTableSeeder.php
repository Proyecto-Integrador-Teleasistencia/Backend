<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear operadores
        $operators = User::factory()
            ->count(10)
            ->state(['role' => 'operator'])
            ->create();

        // Asignar zonas aleatorias a cada operador
        $zones = Zone::all();
        $operators->each(function ($operator) use ($zones) {
            $operator->zones()->attach(
                $zones->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
