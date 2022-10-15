<?php

namespace Tests\Feature\Customer;

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
        $response = $this->makeRequest($this->customerAccount->id . '123', $this->user);

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function get_info()
    {
        $response = $this->makeRequest($this->customerAccount->id, $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'id',
            'first_name',
            'last_name',
            'email',
            'business_name',
            'business_number',
            'business_address',
            'business_phone_code',
            'business_phone_number',
            'business_email',
            'contact_first_name',
            'contact_last_name',
            'contact_phone_code',
            'contact_phone_number',
            'contact_email',
            'contact_email'
        ]);

        $this->assertEquals($responseData['first_name'], $this->customerAccount->first_name);
        $this->assertEquals($responseData['last_name'], $this->customerAccount->last_name);
        $this->assertEquals($responseData['email'], $this->customerAccount->email);

        $this->assertEquals($responseData['business_name'], $this->customerAccount->business_name);
        $this->assertEquals($responseData['business_number'], $this->customerAccount->business_number);
        $this->assertEquals($responseData['business_address'], $this->customerAccount->business_address);

        $this->assertEquals($responseData['business_phone_code'], $this->customerAccount->business_phone_code);
        $this->assertEquals($responseData['business_phone_number'], $this->customerAccount->business_phone_number);
        $this->assertEquals($responseData['business_email'], $this->customerAccount->business_email);

        $this->assertEquals($responseData['contact_first_name'], $this->customerAccount->contact_first_name);
        $this->assertEquals($responseData['contact_last_name'], $this->customerAccount->contact_last_name);
        $this->assertEquals($responseData['contact_phone_code'], $this->customerAccount->contact_phone_code);
        $this->assertEquals($responseData['contact_phone_number'], $this->customerAccount->contact_phone_number);
        $this->assertEquals($responseData['contact_email'], $this->customerAccount->contact_email);
    }

    private function makeRequest(string $id, User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            "/v1/customers/{$id}",
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
