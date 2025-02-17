<?php

namespace Database\Seeders;

use App\Models\Zone;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 5-10 pacientes por zona
        Zone::all()->each(function ($zone) {
            Patient::factory()
                ->count(fake()->numberBetween(5, 10))
                ->create(['zone_id' => $zone->id]);
        });
    }
}
