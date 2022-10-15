<?php

namespace Tests\Feature\Shipment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $account;
    private $shipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
        $this->shipment = $this->createShipment($this->account);
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest($this->shipment->id);
        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function invalid_id()
    {
        $response = $this->makeRequest($this->shipment->id . '123', $this->user);
        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function get_info()
    {
        $response = $this->makeRequest($this->shipment->id, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'id',
            'customer',
            'vehicle',
            'total_pick_up',
            'total_drop_off',
            'tasks',
        ]);

        $this->assertEquals($responseData['id'], $this->shipment->id);
        $this->assertEquals($responseData['customer']['id'], $this->shipment->customer_id);
        $this->assertEquals($responseData['vehicle']['id'], $this->shipment->vehicle_id);
        $this->assertEquals($responseData['total_pick_up'], $this->shipment->total_pick_up);
        $this->assertEquals($responseData['total_drop_off'], $this->shipment->total_drop_off);
        $this->assertEquals($responseData['total'], $this->shipment->total);
        $this->assertEquals(count($responseData['tasks']), $this->shipment->tasks->count());
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            "/v1/shipments/{$id}",
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
