<?php

namespace Tests\Feature\Driver;

use App\Models\Account;
use App\Models\User;
use App\Models\Driver;
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
        $data = $this->getData($this->account);
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
        $data = $this->getData($this->account);
        unset($data['last_name']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('last_name');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_email_is_not_required()
    {
        $data = $this->getData($this->account);
        unset($data['email']);

        $response = $this->makeRequest($data, $this->user);

        $response->assertStatus(201, $response->status());
    }

    /** @test */
    public function the_email_format_is_invalid()
    {
        $data = $this->getData($this->account);
        $data['email'] = 'asdasdasdasda';

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('email');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_identification_number_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['identification_number']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('identification_number');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_licence_number_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['licence_number']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('licence_number');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_phone_code_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['phone_code']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('phone_code');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_phone_number_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['phone_number']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('phone_number');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_vehicle_id_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['vehicle_id']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('vehicle_id');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_vehicle_brand_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['vehicle_brand']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('vehicle_brand');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_vehicle_model_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['vehicle_model']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('vehicle_model');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_vehicle_identification_number_is_required()
    {
        $data = $this->getData($this->account);
        unset($data['vehicle_identification_number']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('vehicle_identification_number');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function can_be_created()
    {
        $data = $this->getData($this->account);
        $response = $this->makeRequest($data, $this->user);

        $response->assertStatus(201, $response->status());

        $driver = Driver::latest()->first();

        $this->assertEquals($data['first_name'], $driver->first_name);
        $this->assertEquals($data['last_name'], $driver->last_name);

        $this->assertEquals($data['email'], $driver->email);

        $this->assertEquals($data['identification_number'], $driver->identification_number);
        $this->assertEquals($data['licence_number'], $driver->licence_number);
        $this->assertEquals($data['phone_code'], $driver->phone_code);
        $this->assertEquals($data['phone_number'], $driver->phone_number);

        $this->assertEquals($data['vehicle_id'], $driver->vehicle->id);

        $this->assertEquals($data['vehicle_brand'], $driver->vehicle_brand);
        $this->assertEquals($data['vehicle_model'], $driver->vehicle_model);
        $this->assertEquals($data['vehicle_identification_number'], $driver->vehicle_identification_number);
    }

    private function getData(Account $account): array
    {
        $vehicle = $this->createVehicle($account);

        return [

            'email' => $this->faker->safeEmail,

            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'identification_number' => 'ID-' . $this->faker->randomNumber,
            'licence_number' => 'LN-' . $this->faker->randomNumber,

            'phone_code' => '+' . rand(1, 150),
            'phone_number' => $this->faker->phoneNumber,

            'vehicle_id' => $vehicle->id,

            'vehicle_brand' => $this->faker->jobTitle,
            'vehicle_model' => $this->faker->jobTitle,
            'vehicle_identification_number' => 'VID-' . $this->faker->randomNumber,

        ];
    }

    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'POST',
            '/v1/drivers',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
