<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create(['nombre' => 'Avisos']);
        Categoria::create(['nombre' => 'Seguimiento según protocolos']);
        Categoria::create(['nombre' => 'Agendas de ausencia domiciliaria y retorno']);
        Categoria::create(['nombre' => 'Atención de emergencias']);
        Categoria::create(['nombre' => 'Comunicaciones no urgentes']);
        Categoria::create(['nombre' => 'Actuación previa']);
    }
}
