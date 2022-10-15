<?php

namespace Tests\Feature\DeliveryZone;

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
    private $deliveryZone;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
        $this->deliveryZone = $this->createDeliveryZone($this->account);
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest($this->deliveryZone->id);

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function invalid_id()
    {
        $response = $this->makeRequest($this->deliveryZone->id . '123', $this->user);

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function get_info()
    {
        $response = $this->makeRequest($this->deliveryZone->id, $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'id',
            'name',
            'type',
            'price_pick_up',
            'price_drop_off',
        ]);

        $this->assertEquals($responseData['id'], $this->deliveryZone->id);
        $this->assertEquals($responseData['name'], $this->deliveryZone->name);
        $this->assertEquals($responseData['type'], $this->deliveryZone->type);

        $this->assertEquals($responseData['price_pick_up'], $this->deliveryZone->price_pick_up);
        $this->assertEquals($responseData['price_drop_off'], $this->deliveryZone->price_drop_off);
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            "/v1/delivery_zones/{$id}",
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
