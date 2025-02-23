<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Call;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CallsTest extends TestCase
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

    public function test_can_get_all_calls()
    {
        Call::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/calls');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
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
