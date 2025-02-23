<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Paciente;
use App\Models\Llamada;
use App\Models\Zona;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;
    private $zone;

    protected function setUp(): void
    {
        parent::setUp();

        $this->zone = Zona::factory()->create(['id' => 1]);
        
        $this->user = User::factory()->create([
            'role' => 'admin',
            'zona_id' => $this->zone->id
        ]);
        
        $this->actingAs($this->user, 'sanctum');
        
        $this->categoria = Categoria::factory()->create();
        $this->subcategoria = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id,
        ]);
    }

    public function test_can_get_all_patients()
    {
        Paciente::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('test-token')->plainTextToken,
        ])->getJson('/api/pacientes');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_can_create_patient()
    {
        $patientData = [
            'nombre' => 'John Doe',
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => '123 Main St',
            'dni' => '12345678A',
            'tarjeta_sanitaria' => 'TS123456789',
            'telefono' => '123456789',
            'zona_id' => $this->zone->id,
        ];

        $response = $this->postJson('/api/pacientes', $patientData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'nombre' => 'John Doe',
                    'direccion' => '123 Main St',
                ]);
    }

    public function test_can_get_single_patient()
    {
        $patient = Paciente::factory()->create();
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('test-token')->plainTextToken,
        ])->getJson("/api/pacientes/{$patient->id}");
    
        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $patient->id,
                    ]
                ]);
    }

    public function test_can_update_patient()
    {
        $patient = Paciente::factory()->create([
            'zona_id' => $this->zone->id
        ]);

        $updateData = [
            'nombre' => 'Updated Name',
            'direccion' => 'Updated Address',
            'fecha_nacimiento' => '1985-06-15',
            'dni' => '98765432B',
            'tarjeta_sanitaria' => 'TS987654321',
            'telefono' => '987654321',
            'zona_id' => $this->zone->id,
        ];

        $response = $this->putJson("/api/pacientes/{$patient->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'nombre' => 'Updated Name',
                    'direccion' => 'Updated Address',
                ]);
    }

    public function test_can_delete_patient()
    {
        // Create a patient in the same zone as the user
        $patient = Paciente::factory()->create([
            'zona_id' => $this->zone->id
        ]);

        $response = $this->deleteJson("/api/pacientes/{$patient->id}");

        $response->assertStatus(204);
        
        // Verify the patient was actually deleted
        $this->assertDatabaseMissing('pacientes', ['id' => $patient->id]);
    }
}
