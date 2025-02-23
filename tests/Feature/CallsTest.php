<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Llamada;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Zona;

class CallsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
    
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $loginResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    
        $this->token = $loginResponse->json('access_token');
    }

    public function test_can_get_all_calls()
    {
        $calls = Llamada::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/llamadas');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data')
                ->assertJson(function (AssertableJson $json) use ($calls) {
                    $json->where('data.0.id', $calls->first()->id)
                        ->where('data.1.id', $calls->get(1)->id)
                        ->where('data.2.id', $calls->last()->id);
                });
    }

    public function test_can_create_call()
    {
        $callData = [
            'patient_id' => 1,
            'type' => 'emergency',
            'description' => 'Test emergency call',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/calls', $callData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'description' => 'Test emergency call',
                ]);
    }

    public function test_can_get_single_call()
    {
        $call = Call::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/calls/{$call->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $call->id,
                    ]
                ]);
    }
}
