<?php

namespace Tests\Feature\Vehicle;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $account;
    private $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
        $this->vehicle = $this->createVehicle($this->account);
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest($this->vehicle->id);

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function invalid_id()
    {
        $response = $this->makeRequest($this->vehicle->id . '123', $this->user);

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function get_info()
    {
        $response = $this->makeRequest($this->vehicle->id, $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'id',
            'name',
            'description',
        ]);

        $this->assertEquals($responseData['id'], $this->vehicle->id);
        $this->assertEquals($responseData['name'], $this->vehicle->name);
        $this->assertEquals($responseData['description'], $this->vehicle->description);
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            "/v1/vehicles/{$id}",
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
