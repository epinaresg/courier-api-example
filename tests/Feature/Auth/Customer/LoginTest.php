<?php

namespace Tests\Feature\Auth\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use App\Models\CustomerAccount;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $customerAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerAccount = $this->createCustomerAccount();
    }

    /** @test */
    public function the_email_credential_is_required()
    {
        $data = $this->getCredentials($this->customerAccount);
        unset($data['email']);

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('email');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_email_credential_has_invalid_format()
    {
        $data = $this->getCredentials($this->customerAccount);
        $data['email'] = 'aaaaaaaaaaa';

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('email');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_password_credential_is_required()
    {
        $data = $this->getCredentials($this->customerAccount);
        unset($data['password']);

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('password');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_password_credential_min_length_8_is_required()
    {
        $data = $this->getCredentials($this->customerAccount);
        $data['password'] = '123456';

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('password');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function sends_invalid_account_key()
    {
        $this->customerAccount->account_id .= '-123';
        $data = $this->getCredentials($this->customerAccount);

        $response = $this->makeRequest($data);

        $this->assertEquals(422, $response->status());
    }

    /** @test */
    public function sends_correct_credentials()
    {
        $customerAccount = $this->createCustomerAccount();
        $data = $this->getCredentials($customerAccount);

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $this->assertEquals(200, $response->status());

        $this->assertEquals($responseData['email'], $data['email']);

        $this->assertTrue(strlen($responseData['auth']['token']) > 1);
    }

    /** @test */
    public function sends_invalid_password_credential()
    {
        $customerAccount = $this->createCustomerAccount();
        $data = $this->getCredentials($customerAccount);
        $data['password'] .= '123';

        $response = $this->makeRequest($data);

        $this->assertEquals(401, $response->status());
    }

    /** @test */
    public function sends_invalid_email_credential()
    {
        $customerAccount = $this->createCustomerAccount();
        $data = $this->getCredentials($customerAccount);
        $data['email'] = 'a@b.com';

        $response = $this->makeRequest($data);

        $this->assertEquals(401, $response->status());
    }

    private function getCredentials(CustomerAccount $customerAccount): array
    {
        $this->setAccountKey($customerAccount->account_id);

        return [
            'email' => $customerAccount->email,
            'password' => '12345678',
        ];
    }

    private function makeRequest($data): TestResponse
    {
        return $this->json(
            'POST',
            '/v1/auth/customers/login',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
                'Account-Key' => $this->getAccountKey(),
            ]
        );
    }
}
