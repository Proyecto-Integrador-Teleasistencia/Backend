<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_get_all_patients()
    {
        Patient::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/patients');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_can_create_patient()
    {
        $patientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'address' => 'Test Address',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/patients', $patientData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                ]);
    }

    public function test_can_get_single_patient()
    {
        $patient = Patient::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/patients/{$patient->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $patient->id,
                    ]
                ]);
    }

    public function test_can_update_patient()
    {
        $patient = Patient::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/patients/{$patient->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com',
                ]);
    }
}
