<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ZonesTableSeeder;
use Database\Seeders\PatientsTableSeeder;
use Database\Seeders\ContactPersonsTableSeeder;
use Database\Seeders\OperatorsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\SubcategoriesTableSeeder;
use Database\Seeders\AlertsTableSeeder;
use Database\Seeders\CallsTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ZonesTableSeeder::class,
            PatientsTableSeeder::class,
            ContactPersonsTableSeeder::class,
            OperatorsTableSeeder::class,
            CategoriesTableSeeder::class,
            SubcategoriesTableSeeder::class,
            AlertsTableSeeder::class,
            CallsTableSeeder::class,
        ]);

        // Create default admin user
        User::factory()->create([
            'nombre' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }
}
