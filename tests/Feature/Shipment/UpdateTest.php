<?php

namespace Tests\Feature\Shipment;

use App\Models\User;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $account;
    private $shipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;
        $this->shipment = $this->createShipment($this->account);
    }

    /** @test */
    public function without_authorization()
    {
        $user = $this->createUser();
        $shipment = $this->createShipment($user->account);
        $response = $this->makeRequest($shipment->id);

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function the_name_is_required()
    {
        $user = $this->createUser();
        $shipment = $this->createShipment($user->account);

        $data = $this->getData();
        unset($data['name']);

        $response = $this->makeRequest(
            $shipment->id,
            $data,
            $user
        );
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('name');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_description_is_required()
    {
        $user = $this->createUser();
        $shipment = $this->createShipment($user->account);

        $data = $this->getData();
        unset($data['description']);

        $response = $this->makeRequest(
            $shipment->id,
            $data,
            $user
        );
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('description');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function invalid_id()
    {
        $user = $this->createUser();
        $shipment = $this->createShipment($user->account);

        $data = $this->getData();

        $response = $this->makeRequest(
            $shipment->id . '123',
            $data,
            $user
        );

        $response->assertStatus(404, $response->status());
    }

    /** @test */
    public function can_be_updated()
    {
        $user = $this->createUser();
        $shipment = $this->createShipment($user->account);

        $data = $this->getData();

        $response = $this->makeRequest(
            $shipment->id,
            $data,
            $user
        );

        $response->assertStatus(201, $response->status());

        $shipment = Shipment::where('id', $shipment->id)->first();

        $this->assertEquals($data['name'], $shipment->name);
        $this->assertEquals($data['description'], $shipment->description);
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
            "/v1/shipments/{$id}",
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
