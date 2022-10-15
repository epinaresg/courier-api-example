<?php

namespace Tests\Feature\DeliveryZone;

use App\Models\User;
use App\Models\DeliveryZone;
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
            $this->createDeliveryZone($this->account);
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
        $deliveryZone = DeliveryZone::inRandomOrder()->first();

        $response = $this->makeRequest([
            'term' => $deliveryZone->name
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'items',
            'pagination'
        ]);

        $this->assertEquals($responseData['pagination']['total'], 1);
        $this->assertEquals($responseData['items'][0]['name'], $deliveryZone->name);
        $this->assertEquals($responseData['items'][0]['type'], $deliveryZone->type);

        $this->assertEquals($responseData['items'][0]['price_pick_up'], $deliveryZone->price_pick_up);
        $this->assertEquals($responseData['items'][0]['price_drop_off'], $deliveryZone->price_drop_off);
    }


    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            '/v1/delivery_zones',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
