<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Llamada;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Zona;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Paciente;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Paciente;
use Illuminate\Testing\Fluent\AssertableJson;

class CallsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;
    private $zone;
    private $categoria;
    private $subcategoria;
    private $paciente;
    private $zone;
    private $categoria;
    private $subcategoria;
    private $paciente;

    protected function setUp(): void
    {
        parent::setUp();
    
        $zone = Zona::factory()->create(['id' => 1]);

        // Create required test data
        $this->zone = Zona::factory()->create(['id' => 1]);
        $this->categoria = Categoria::factory()->create();
        $this->subcategoria = Subcategoria::factory()->create([
            'categoria_id' => $this->categoria->id,
        ]);
        $this->paciente = Paciente::factory()->create([
            'zona_id' => $this->zone->id,
        ]);
    }

    public function test_can_get_all_calls()
    {
        $calls = Llamada::factory()->count(3)->create();
    
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/llamadas');
    
    
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    
        $responseIds = collect($response->json('data'))->pluck('id')->toArray();
    
        foreach ($calls as $call) {
            $this->assertContains($call->id, $responseIds);
        }
            ->assertJsonCount(3, 'data');
    
        $responseIds = collect($response->json('data'))->pluck('id')->toArray();
    
        foreach ($calls as $call) {
            $this->assertContains($call->id, $responseIds);
        }
    }

    public function test_can_create_call()
    {
        $callData = [
            'fecha_hora' => now()->format('Y-m-d H:i:s'),
            'tipo_llamada' => 'entrante',
            'duracion' => 300,
            'descripcion' => 'Test emergency call',
            'operador_id' => $this->user->id,
            'paciente_id' => $this->paciente->id,
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id
            'fecha_hora' => now()->format('Y-m-d H:i:s'),
            'tipo_llamada' => 'entrante',
            'duracion' => 300,
            'descripcion' => 'Test emergency call',
            'operador_id' => $this->user->id,
            'paciente_id' => $this->paciente->id,
            'categoria_id' => $this->categoria->id,
            'subcategoria_id' => $this->subcategoria->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/llamadas', $callData);

        // Debugging: Print the response content
        // echo "\nResponse Content:\n";
        // print_r($response->json());
        // echo "\n";

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'fecha_hora',
                    'tipo_llamada',
                    'duracion',
                    'descripcion',
                ],
            ]);
    }

    public function test_can_get_single_call()
    {
        $call = Llamada::factory()->create();
        $call = Llamada::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/llamadas/{$call->id}");
        ])->getJson("/api/llamadas/{$call->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $call->id,
                    ]
                ]);
    }
}
