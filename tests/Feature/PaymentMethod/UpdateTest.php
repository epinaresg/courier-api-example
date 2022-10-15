<?php

namespace Tests\Feature\PaymentMethod;

use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $account;
    private $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
        $this->paymentMethod = $this->createPaymentMethod($this->account);
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest($this->paymentMethod->id);

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function the_name_is_required()
    {
        $data = $this->getData();
        unset($data['name']);

        $response = $this->makeRequest(
            $this->paymentMethod->id,
            $data,
            $this->user
        );
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('name');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_description_is_required()
    {
        $data = $this->getData();
        unset($data['description']);

        $response = $this->makeRequest(
            $this->paymentMethod->id,
            $data,
            $this->user
        );
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('description');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function invalid_id()
    {
        $data = $this->getData();

        $response = $this->makeRequest(
            $this->paymentMethod->id . '-123',
            $data,
            $this->user
        );

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function can_be_updated()
    {
        $data = $this->getData();

        $response = $this->makeRequest(
            $this->paymentMethod->id,
            $data,
            $this->user
        );

        $response->assertStatus(201, $response->status());

        $paymentMethod = PaymentMethod::where('id', $this->paymentMethod->id)->first();

        $this->assertEquals($data['name'], $paymentMethod->name);
        $this->assertEquals($data['description'], $paymentMethod->description);
    }

    private function getData(): array
    {
        return [
            'name' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
        ];
    }


    private function makeRequest(string $id, array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'PUT',
            "/v1/payment_methods/{$id}",
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
