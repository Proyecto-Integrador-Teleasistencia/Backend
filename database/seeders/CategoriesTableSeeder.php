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
        $categories = [
            ['nombre' => 'Avisos'],
            ['nombre' => 'Seguimiento según protocolos'],
            ['nombre' => 'Agendas de ausencia domiciliaria y retorno'],
            ['nombre' => 'Atención de emergencias'],
            ['nombre' => 'Comunicaciones no urgentes']
        ];

        foreach ($categories as $category) {
            Categoria::create($category);
        }
    }
}
