<?php

namespace Tests\Feature\Auth\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use App\Models\Account;
use App\Models\Customer;
use App\Models\CustomerAccount;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = $this->createAccount();

        $this->setAccountKey($this->account->id);
    }

    /** @test */
    public function the_first_name_is_required()
    {
        $data = $this->getData();
        unset($data['first_name']);

        $response = $this->makeRequest($data);
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

        $response = $this->makeRequest($data);
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

        $response = $this->makeRequest($data);
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

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('email');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_password_is_required()
    {
        $data = $this->getData();
        unset($data['password']);

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('password');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_password_min_length_8()
    {
        $data = $this->getData();
        $data['password'] = '123456';
        $data['password_confirmation'] = '123456';

        $response = $this->makeRequest($data);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('password');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_password_confirmation_is_required()
    {
        $data = $this->getData();
        unset($data['password_confirmation']);

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('password');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_account_key_is_required()
    {
        $data = $this->getData();
        $this->setAccountKey('');

        $response = $this->makeRequest($data);

        $response->assertStatus(422, $response->status());
    }

    /** @test */
    public function the_account_key_is_not_valid()
    {
        $this->setAccountKey($this->account->id . '-123');

        $data = $this->getData();

        $response = $this->makeRequest($data);

        $response->assertStatus(422, $response->status());
    }

    /** @test */
    public function can_be_registered()
    {
        $this->setAccountKey($this->account->id);
        $data = $this->getData();

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());


        $this->assertCount(1, Customer::all());

        $customer = Customer::first();
        $account = Account::first();

        $this->assertEquals($responseData['first_name'], $customer->customerAccounts[0]->first_name);
        $this->assertEquals($responseData['first_name'], $data['first_name']);

        $this->assertEquals($responseData['last_name'], $customer->customerAccounts[0]->last_name);
        $this->assertEquals($responseData['last_name'], $data['last_name']);

        $this->assertEquals($responseData['email'], $customer->email);
        $this->assertEquals($responseData['email'], $data['email']);

        $this->assertTrue(strlen($responseData['auth']['token']) > 1);

        $this->assertTrue($customer->customerAccounts->count() === 1);

        $this->assertEquals($account->id, $customer->customerAccounts[0]->account_id);
        $this->assertEquals($customer->id, $customer->customerAccounts[0]->customer_id);
    }


    /** @test */
    public function registered_email_account_cant_be_registered()
    {
        $customerAccount = $this->createCustomerAccount($this->account);

        $data = $this->getData();
        $data['email'] = $customerAccount->email;

        $response = $this->makeRequest($data);

        $response->assertStatus(422, $response->status());

        $this->assertCount(1, Customer::all());
        $this->assertCount(1, Account::all());
    }

    /** @test */
    public function a_non_registered_email_new_account_can_be_registered()
    {
        $customerAccount = $this->createCustomerAccount($this->account);

        $account = $this->createAccount();
        $this->setAccountKey($account->id);

        $data = $this->getData();
        $data['email'] = $customerAccount->email;
		sleep(1);

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $this->assertCount(1, Customer::all());

        $customer = Customer::first();

        $this->assertEquals($responseData['first_name'], $data['first_name']);
        $this->assertEquals($responseData['last_name'], $data['last_name']);
        $this->assertEquals($responseData['email'], $data['email']);
        $this->assertTrue(strlen($responseData['auth']['token']) > 1);

        $this->assertTrue($customer->customerAccounts->count() === 2);

        $customerAccounts = $customer->customerAccounts->sortBy('created_at')->values();

        $this->assertEquals($account->id, $customerAccounts[1]->account_id);
        $this->assertEquals($customer->id, $customerAccounts[1]->customer_id);
    }


    private function getData(): array
    {
        return [
            'first_name' => 'Test customer first name',
            'last_name' => 'Test customer last name',
            'email' => 'test@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];
    }

    private function makeRequest(array $data): TestResponse
    {
        return $this->json(
            'POST',
            '/v1/auth/customers/register',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
                'Account-Key' => $this->getAccountKey(),
            ]
        );
    }
}
