<?php

namespace Tests\Feature\Driver;

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
    private $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
        $this->driver = $this->createDriver($this->account);
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest($this->driver->id);

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function invalid_id()
    {
        $response = $this->makeRequest($this->driver->id . '123', $this->user);

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function get_info()
    {
        $response = $this->makeRequest($this->driver->id, $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'id',
            'first_name',
            'last_name',
            'email',
            'identification_number',
            'licence_number',
            'phone_code',
            'phone_number',
            'vehicle',
            'vehicle_brand',
            'vehicle_model',
            'vehicle_identification_number',
        ]);

        $this->assertEquals($responseData['id'], $this->driver->id);

        $this->assertEquals($responseData['first_name'], $this->driver->first_name);
        $this->assertEquals($responseData['last_name'], $this->driver->last_name);

        $this->assertEquals($responseData['email'], $this->driver->email);

        $this->assertEquals($responseData['identification_number'], $this->driver->identification_number);
        $this->assertEquals($responseData['licence_number'], $this->driver->licence_number);
        $this->assertEquals($responseData['phone_code'], $this->driver->phone_code);
        $this->assertEquals($responseData['phone_number'], $this->driver->phone_number);

        $this->assertEquals($responseData['vehicle']['id'], $this->driver->vehicle->id);
        $this->assertEquals($responseData['vehicle']['name'], $this->driver->vehicle->name);
        $this->assertEquals($responseData['vehicle']['description'], $this->driver->vehicle->description);

        $this->assertEquals($responseData['vehicle_brand'], $this->driver->vehicle_brand);
        $this->assertEquals($responseData['vehicle_model'], $this->driver->vehicle_model);
        $this->assertEquals($responseData['vehicle_identification_number'], $this->driver->vehicle_identification_number);
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            "/v1/drivers/{$id}",
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
