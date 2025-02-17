<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'birth_date' => fake()->dateTimeBetween('-90 years', '-60 years'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'dni' => fake()->unique()->numerify('#########'),
            'health_card' => fake()->unique()->numerify('SIP########'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail() . '.patient',
            'zone_id' => Zone::factory(),
            'personal_situation' => fake()->optional()->paragraph(),
            'health_condition' => fake()->optional()->paragraph(),
            'home_condition' => fake()->optional()->paragraph(),
            'autonomy_level' => fake()->optional()->randomElement(['Dependiente', 'Semi-dependiente', 'Autónomo']),
            'economic_situation' => fake()->optional()->paragraph(),
        ];
    }
}
