<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    /** @test */
    public function the_email_credential_is_required()
    {
        $data = $this->getCredentials($this->user);
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
        $data = $this->getCredentials($this->user);
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
        $data = $this->getCredentials($this->user);
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
        $data = $this->getCredentials($this->user);
        $data['password'] = '123456';

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('password');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function user_sends_correct_credentials()
    {
        $data = $this->getCredentials($this->user);

        $response = $this->makeRequest($data);

        $this->assertEquals(200, $response->status());

        $responseData = $response->decodeResponseJson();

        $this->assertEquals($responseData['email'], $data['email']);

        $this->assertTrue(strlen($responseData['auth']['token']) > 1);
    }

    /** @test */
    public function user_sends_invalid_credentials()
    {
        $data = $this->getCredentials($this->user);
        $data['password'] = '123456789';

        $response = $this->makeRequest($data);

        $this->assertEquals(401, $response->status());
    }

    private function getCredentials(User $user): array
    {
        return [
            'email' => $user->email,
            'password' => '12345678'
        ];
    }

    private function makeRequest($data): TestResponse
    {
        return $this->json(
            'POST',
            '/v1/auth/login',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
