<?php

namespace Tests\Feature\Customer;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $account;
    private $customerAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
        $this->customerAccount = $this->createCustomerAccount($this->account);
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest($this->customerAccount->id);

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function invalid_id()
    {
        $response = $this->makeRequest(
            $this->customerAccount->id . '123',
            $this->user
        );

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function can_be_deleted()
    {
        $response = $this->makeRequest(
            $this->customerAccount->id,
            $this->user
        );

        $response->assertStatus(201, $response->status());

        $this->assertNotEquals($this->customerAccount, null);
        $this->assertNotEquals($this->customerAccount->fresh()->deleted_at, null);
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'DELETE',
            "/v1/customers/{$id}",
            [],
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
