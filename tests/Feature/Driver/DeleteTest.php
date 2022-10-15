<?php

namespace Tests\Feature\Driver;

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
        $response = $this->makeRequest(
            $this->driver->id . '123',
            $this->user
        );

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function customer_can_be_deleted()
    {
        $response = $this->makeRequest(
            $this->driver->id,
            $this->user
        );

        $response->assertStatus(201, $response->status());

        $this->assertNotEquals($this->driver, null);
        $this->assertNotEquals($this->driver->fresh()->deleted_at, null);
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'DELETE',
            "/v1/drivers/{$id}",
            [],
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
