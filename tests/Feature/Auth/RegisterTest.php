<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class RegisterTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
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
    public function can_be_registered()
    {
        $data = $this->getData();

        $response = $this->makeRequest($data);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $this->assertCount(1, User::all());
        $this->assertCount(1, Account::all());

        $user = User::first();
        $account = Account::first();

        $this->assertEquals($responseData['first_name'], $user->first_name);
        $this->assertEquals($responseData['first_name'], $data['first_name']);

        $this->assertEquals($responseData['last_name'], $user->last_name);
        $this->assertEquals($responseData['last_name'], $data['last_name']);

        $this->assertEquals($responseData['email'], $user->email);
        $this->assertEquals($responseData['email'], $data['email']);

        $this->assertEquals($responseData['account']['key'], $account->account_key);

        $this->assertTrue(strlen($responseData['auth']['token']) > 1);

        $this->assertTrue($user->roles->count() === 1);
    }


    /** @test */
    public function a_registered_email_cant_be_registered_again()
    {
        $user = $this->createUser();

        $data = $this->getData();
        $data['email'] = $user->email;

        $response = $this->makeRequest($data);

        $response->assertStatus(422, $response->status());

        $this->assertCount(1, User::all());
        $this->assertCount(1, Account::all());
    }

    private function getData(): array
    {
        return [
            'first_name' => 'Test first name',
            'last_name' => 'Test last name',
            'email' => 'test@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];
    }

    private function makeRequest(array $data): TestResponse
    {
        return $this->json(
            'POST',
            '/v1/auth/register',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
