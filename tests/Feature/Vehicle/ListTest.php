<?php

namespace Tests\Feature\Vehicle;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $account;
    private $qty;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;

        $this->qty = rand(5, 25);
        for ($i = 0; $i < $this->qty; $i++) {
            $this->createShipment($this->account);
        }
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest();

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function get_list()
    {
        $response = $this->makeRequest([], $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'items',
            'pagination'
        ]);

        $this->assertEquals($responseData['pagination']['total'], $this->qty);
    }

    /** @test */
    public function get_list_filter_by_term_name()
    {
        $vehicle = Vehicle::inRandomOrder()->first();

        $response = $this->makeRequest([
            'term' => $vehicle->name
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'items',
            'pagination'
        ]);

        $this->assertEquals($responseData['pagination']['total'], 1);
        $this->assertEquals($responseData['items'][0]['name'], $vehicle->name);
        $this->assertEquals($responseData['items'][0]['description'], $vehicle->description);
    }


    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            '/v1/vehicles',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
