<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 3-5 subcategorías por cada categoría
        Category::all()->each(function ($category) {
            Subcategory::factory()
                ->count(fake()->numberBetween(3, 5))
                ->create(['category_id' => $category->id]);
        });
    }
}
