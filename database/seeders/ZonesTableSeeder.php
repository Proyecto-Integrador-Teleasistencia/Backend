<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('zonas')->insert([
            ['nombre' => 'Alicante', 'codigo' => 'ALC'],
            ['nombre' => 'Castellón', 'codigo' => 'CSN'],
            ['nombre' => 'Valencia', 'codigo' => 'VLC']
        ]);
    }
}
