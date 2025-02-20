<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Categoría 1: Avisos
            ['nombre' => 'Avisos de medicación', 'categoria_id' => 1],
            ['nombre' => 'Avisos especiales o por alerta', 'categoria_id' => 1],
            
            // Categoría 2: Seguimiento según protocolos
            ['nombre' => 'Seguimiento después de emergencias', 'categoria_id' => 2],
            ['nombre' => 'Seguimiento por procesos de duelo', 'categoria_id' => 2],
            ['nombre' => 'Seguimiento por altas hospitalarias', 'categoria_id' => 2],
            
            // Categoría 3: Agendas de ausencia
            ['nombre' => 'Suspensión temporal del servicio', 'categoria_id' => 3],
            ['nombre' => 'Retornos o fin de la ausencia', 'categoria_id' => 3],
            
            // Categoría 4: Atención de emergencias
            ['nombre' => 'Emergencias sociales', 'categoria_id' => 4],
            ['nombre' => 'Emergencias sanitarias', 'categoria_id' => 4],
            ['nombre' => 'Crisis de soledad o angustia', 'categoria_id' => 4],
            ['nombre' => 'Alarma sin respuesta', 'categoria_id' => 4],
            
            // Categoría 5: Comunicaciones no urgentes
            ['nombre' => 'Notificar ausencias o retornos', 'categoria_id' => 5],
            ['nombre' => 'Modificar datos personales', 'categoria_id' => 5],
            ['nombre' => 'Llamadas accidentales', 'categoria_id' => 5],
            ['nombre' => 'Petición de información', 'categoria_id' => 5],
            ['nombre' => 'Formulación de sugerencias, quejas o reclamaciones', 'categoria_id' => 5],
            ['nombre' => 'Llamadas sociales (para saludar o hablar con el personal)', 'categoria_id' => 5],
            ['nombre' => 'Registrar citas médicas tras una llamada', 'categoria_id' => 5],
            ['nombre' => 'Otros tipos de llamadas', 'categoria_id' => 5]
        ];

        foreach ($subcategories as $subcategory) {
            Subcategoria::create($subcategory);
        }
    }
}
