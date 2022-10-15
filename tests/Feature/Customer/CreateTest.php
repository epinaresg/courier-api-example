<?php

namespace Tests\Feature\Customer;

use App\Models\CustomerAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest();

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function the_first_name_is_required()
    {
        $data = $this->getData();
        unset($data['first_name']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('first_name');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_last_name_is_required()
    {
        $data = $this->getData();
        unset($data['last_name']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('last_name');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_email_is_required()
    {
        $data = $this->getData();
        unset($data['email']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('email');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_email_has_invalid_format()
    {
        $data = $this->getData();
        $data['email'] = 'aaaaaaaaaaa';

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('email');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function can_be_created()
    {
        $data = $this->getData();
        $response = $this->makeRequest($data, $this->user);

        $response->assertStatus(201, $response->status());

        $customerAccount = CustomerAccount::latest()->first();

        $this->assertEquals($data['first_name'], $customerAccount->first_name);
        $this->assertEquals($data['last_name'], $customerAccount->last_name);
        $this->assertEquals($data['email'], $customerAccount->email);

        $this->assertEquals($data['business_name'], $customerAccount->business_name);
        $this->assertEquals($data['business_number'], $customerAccount->business_number);
        $this->assertEquals($data['business_address'], $customerAccount->business_address);

        $this->assertEquals($data['business_phone_code'], $customerAccount->business_phone_code);
        $this->assertEquals($data['business_phone_number'], $customerAccount->business_phone_number);
        $this->assertEquals($data['business_email'], $customerAccount->business_email);

        $this->assertEquals($data['contact_first_name'], $customerAccount->contact_first_name);
        $this->assertEquals($data['contact_last_name'], $customerAccount->contact_last_name);
        $this->assertEquals($data['contact_phone_code'], $customerAccount->contact_phone_code);
        $this->assertEquals($data['contact_phone_number'], $customerAccount->contact_phone_number);
        $this->assertEquals($data['contact_email'], $customerAccount->contact_email);
    }

    /** @test */
    public function registered_email_cant_be_created()
    {
        $customerAccount = $this->createCustomerAccount($this->account);

        $data = $this->getData();
        $data['email'] = $customerAccount->email;

        $response = $this->makeRequest($data, $this->user);

        $response->assertStatus(422, $response->status());
    }

    private function getData(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,


            'business_name' => $this->faker->company,
            'business_number' => $this->faker->ean13,
            'business_address' => $this->faker->address,

            'business_phone_code' => '+' . rand(1, 150),
            'business_phone_number' => $this->faker->phoneNumber,
            'business_email' => $this->faker->safeEmail,

            'contact_first_name' => $this->faker->firstName,
            'contact_last_name' => $this->faker->lastName,
            'contact_phone_code' => '+' . rand(1, 150),
            'contact_phone_number' => $this->faker->phoneNumber,
            'contact_email' => $this->faker->safeEmail,
        ];
    }

    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'POST',
            '/v1/customers',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
