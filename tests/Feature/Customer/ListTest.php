<?php

namespace Tests\Feature\Customer;

use App\Models\CustomerAccount;
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
            $this->createCustomerAccount($this->account);
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
    public function get_list_filter_by_term_email()
    {
        $customerAccount = CustomerAccount::inRandomOrder()->first();

        $response = $this->makeRequest([
            'term' => $customerAccount->email
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'items',
            'pagination'
        ]);

        $this->assertEquals($responseData['pagination']['total'], 1);
        $this->assertEquals($responseData['items'][0]['email'], $customerAccount->email);
        $this->assertEquals($responseData['items'][0]['first_name'], $customerAccount->first_name);
        $this->assertEquals($responseData['items'][0]['last_name'], $customerAccount->last_name);
    }

    /** @test */
    public function get_list_filter_by_term_first_name()
    {
        $customerAccount = CustomerAccount::inRandomOrder()->first();

        $response = $this->makeRequest([
            'term' => $customerAccount->first_name
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'items',
            'pagination'
        ]);

        $this->assertEquals($responseData['pagination']['total'], 1);
        $this->assertEquals($responseData['items'][0]['email'], $customerAccount->email);
        $this->assertEquals($responseData['items'][0]['first_name'], $customerAccount->first_name);
        $this->assertEquals($responseData['items'][0]['last_name'], $customerAccount->last_name);
    }

    /** @test */
    public function get_list_filter_by_term_last_name()
    {
        $customerAccount = CustomerAccount::inRandomOrder()->first();

        $response = $this->makeRequest([
            'term' => $customerAccount->last_name
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure([
            'items',
            'pagination'
        ]);

        $this->assertEquals($responseData['pagination']['total'], 1);
        $this->assertEquals($responseData['items'][0]['email'], $customerAccount->email);
        $this->assertEquals($responseData['items'][0]['first_name'], $customerAccount->first_name);
        $this->assertEquals($responseData['items'][0]['last_name'], $customerAccount->last_name);
    }

    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            '/v1/customers',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
