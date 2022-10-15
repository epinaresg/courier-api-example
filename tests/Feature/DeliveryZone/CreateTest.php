<?php

namespace Tests\Feature\DeliveryZone;

use App\Models\User;
use App\Models\DeliveryZone;
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
    public function the_name_is_required()
    {
        $data = $this->getData();
        unset($data['name']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('name');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_type_is_required()
    {
        $data = $this->getData();
        unset($data['type']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('type');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function can_be_created()
    {
        $data = $this->getData();
        $response = $this->makeRequest($data, $this->user);

        $response->assertStatus(201, $response->status());

        $deliveryZone = DeliveryZone::latest()->first();

        $this->assertEquals($data['name'], $deliveryZone->name);
        $this->assertEquals($data['type'], $deliveryZone->type);

        $this->assertEquals($data['price_pick_up'], $deliveryZone->price_pick_up);
        $this->assertEquals($data['price_drop_off'], $deliveryZone->price_drop_off);
    }

    private function getData(): array
    {
        $enumDeliveryZones = config('enum.delivery_zones');
        shuffle($enumDeliveryZones);

        return [
            'name' => $this->faker->word,
            'type' => array_shift($enumDeliveryZones),
            'price_pick_up' => rand(9, 15) . '.00',
            'price_drop_off' =>  rand(9, 15) . '.00',
        ];
    }

    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'POST',
            '/v1/delivery_zones',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
