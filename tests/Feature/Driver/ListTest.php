<?php

namespace Tests\Feature\Driver;

use App\Models\Driver;
use App\Models\User;
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
            $this->createDriver($this->account);
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
    public function get_list_filter_by_term_first_name()
    {
        $driver = Driver::inRandomOrder()->first();

        $response = $this->makeRequest([
            'term' => $driver->first_name
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'items',
            'pagination'
        ]);

        $this->assertEquals($responseData['pagination']['total'], 1);

        $this->assertEquals($responseData['items'][0]['id'], $driver->id);

        $this->assertEquals($responseData['items'][0]['first_name'], $driver->first_name);
        $this->assertEquals($responseData['items'][0]['last_name'], $driver->last_name);

        $this->assertEquals($responseData['items'][0]['email'], $driver->email);

        $this->assertEquals($responseData['items'][0]['identification_number'], $driver->identification_number);
        $this->assertEquals($responseData['items'][0]['licence_number'], $driver->licence_number);
        $this->assertEquals($responseData['items'][0]['phone_code'], $driver->phone_code);
        $this->assertEquals($responseData['items'][0]['phone_number'], $driver->phone_number);

        $this->assertEquals($responseData['items'][0]['vehicle']['id'], $driver->vehicle->id);
        $this->assertEquals($responseData['items'][0]['vehicle']['name'], $driver->vehicle->name);
        $this->assertEquals($responseData['items'][0]['vehicle']['description'], $driver->vehicle->description);

        $this->assertEquals($responseData['items'][0]['vehicle_brand'], $driver->vehicle_brand);
        $this->assertEquals($responseData['items'][0]['vehicle_model'], $driver->vehicle_model);
        $this->assertEquals($responseData['items'][0]['vehicle_identification_number'], $driver->vehicle_identification_number);
    }


    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            '/v1/drivers',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
