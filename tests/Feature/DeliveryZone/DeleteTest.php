<?php

namespace Tests\Feature\DeliveryZone;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
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
        $response = $this->makeRequest(
            $this->deliveryZone->id . '123',
            $this->user
        );

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function customer_can_be_deleted()
    {
        $response = $this->makeRequest(
            $this->deliveryZone->id,
            $this->user
        );

        $response->assertStatus(201, $response->status());

        $this->assertNotEquals($this->deliveryZone, null);
        $this->assertNotEquals($this->deliveryZone->fresh()->deleted_at, null);
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'DELETE',
            "/v1/delivery_zones/{$id}",
            [],
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
