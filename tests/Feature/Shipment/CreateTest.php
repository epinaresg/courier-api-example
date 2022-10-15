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

class CreateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $account;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');

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
    public function the_customer_account_id_is_required()
    {
        $data = $this->getData();
        unset($data['customer_account_id']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('customer_account_id');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_vehicle_id_is_required()
    {
        $data = $this->getData();
        unset($data['vehicle_id']);

        $response = $this->makeRequest($data, $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('vehicle_id');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function at_least_one_task_is_required()
    {
        $data = $this->getData(1);
        unset($data['tasks']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_type_in_a_task_item_is_required()
    {
        $data = $this->getData(3);
        unset($data['tasks'][1]['type']);
        unset($data['tasks'][2]['type']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.1.type');
        $response->assertJsonValidationErrorFor('tasks.2.type');
        $this->assertEquals(count($responseData['errors']), 2);
    }

    /** @test */
    public function the_package_content_in_a_task_item_is_required()
    {
        $data = $this->getData(5);
        unset($data['tasks'][1]['package_content']);
        unset($data['tasks'][2]['package_content']);
        unset($data['tasks'][4]['package_content']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.1.package_content');
        $response->assertJsonValidationErrorFor('tasks.2.package_content');
        $response->assertJsonValidationErrorFor('tasks.4.package_content');
        $this->assertEquals(count($responseData['errors']), 3);
    }

    /** @test */
    public function the_address_in_a_task_item_is_required()
    {
        $data = $this->getData(3);
        unset($data['tasks'][0]['address']);
        unset($data['tasks'][1]['address']);
        unset($data['tasks'][2]['address']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.0.address');
        $response->assertJsonValidationErrorFor('tasks.1.address');
        $response->assertJsonValidationErrorFor('tasks.2.address');
        $this->assertEquals(count($responseData['errors']), 3);
    }

    /** @test */
    public function the_delivery_zone_id_in_a_task_item_is_required()
    {
        $data = $this->getData(4);
        unset($data['tasks'][1]['delivery_zone_id']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.1.delivery_zone_id');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_contact_full_name_in_a_task_item_is_required()
    {
        $data = $this->getData(3);
        unset($data['tasks'][0]['contact_full_name']);
        unset($data['tasks'][2]['contact_full_name']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.0.contact_full_name');
        $response->assertJsonValidationErrorFor('tasks.2.contact_full_name');
        $this->assertEquals(count($responseData['errors']), 2);
    }

    /** @test */
    public function the_contact_phone_code_in_a_task_item_is_required()
    {
        $data = $this->getData(2);
        unset($data['tasks'][1]['contact_phone_code']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.1.contact_phone_code');
        $this->assertEquals(count($responseData['errors']), 1);
    }

    /** @test */
    public function the_contact_phone_number_in_a_task_item_is_required()
    {
        $data = $this->getData(4);
        unset($data['tasks'][0]['contact_phone_number']);
        unset($data['tasks'][1]['contact_phone_number']);
        unset($data['tasks'][2]['contact_phone_number']);
        unset($data['tasks'][3]['contact_phone_number']);

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.0.contact_phone_number');
        $response->assertJsonValidationErrorFor('tasks.1.contact_phone_number');
        $response->assertJsonValidationErrorFor('tasks.2.contact_phone_number');
        $response->assertJsonValidationErrorFor('tasks.3.contact_phone_number');
        $this->assertEquals(count($responseData['errors']), 4);
    }

    /** @test */
    public function the_contact_email_in_a_task_item_not_have_a_valid_format()
    {
        $data = $this->getData(2);
        $data['tasks'][0]['contact_email'] = 'abc';
        $data['tasks'][1]['contact_email'] = 'def';

        $response = $this->makeRequest($data, $this->user);
        $responseData = $response->decodeResponseJson();

        $response->assertStatus(422, $response->status());
        $response->assertJsonValidationErrorFor('tasks.0.contact_email');
        $response->assertJsonValidationErrorFor('tasks.1.contact_email');
        $this->assertEquals(count($responseData['errors']), 2);
    }

    /** @test */
    public function can_be_created()
    {
        $this->withExceptionHandling();
        $data = $this->getData();
        $response = $this->makeRequest($data, $this->user);

        $response->assertStatus(201, $response->status());
        $shipment = Shipment::latest()->first();

        $this->assertEquals($data['customer_account_id'], $shipment->customerAccount->id);
        $this->assertEquals($data['vehicle_id'], $shipment->vehicle->id);


        $qtyTasksItems = count($data['tasks']);
        $taskItem = rand(0, $qtyTasksItems - 1);

        $shipmentTasks = $shipment->tasks->sortBy('order')->values();

        $this->assertEquals($data['tasks'][$taskItem]['type'], $shipmentTasks[$taskItem]->type);
        $this->assertEquals($data['tasks'][$taskItem]['date'], $shipmentTasks[$taskItem]->date->format('Y-m-d'));

        $this->assertEquals($data['tasks'][$taskItem]['package_content'], $shipmentTasks[$taskItem]->package_content);
        $this->assertEquals($data['tasks'][$taskItem]['address'], $shipmentTasks[$taskItem]->address);
        $this->assertEquals($data['tasks'][$taskItem]['address_reference'], $shipmentTasks[$taskItem]->address_reference);

        $this->assertEquals($data['tasks'][$taskItem]['delivery_zone_id'], $shipmentTasks[$taskItem]->deliveryZone->id);

        $this->assertEquals($data['tasks'][$taskItem]['contact_full_name'], $shipmentTasks[$taskItem]->contact_full_name);
        $this->assertEquals($data['tasks'][$taskItem]['contact_phone_code'], $shipmentTasks[$taskItem]->contact_phone_code);
        $this->assertEquals($data['tasks'][$taskItem]['contact_phone_number'], $shipmentTasks[$taskItem]->contact_phone_number);
        $this->assertEquals($data['tasks'][$taskItem]['contact_email'], $shipmentTasks[$taskItem]->contact_email);

        $this->assertEquals($data['tasks'][$taskItem]['payment_method_id'], $shipmentTasks[$taskItem]->paymentMethod->id);

        $this->assertEquals($data['tasks'][$taskItem]['total_receivable'], $shipmentTasks[$taskItem]->total_receivable);
    }

    private function getData(?int $tasksItems = null): array
    {
        $customerAccount = $this->createCustomerAccount($this->account);
        $vehicle = $this->createVehicle($this->account);
        $deliveryZone = $this->createDeliveryZone($this->account);
        $paymentMethod = $this->createPaymentMethod($this->account);

        $tasks = [];
        $types = config('enum.task_types');

        if (is_null($tasksItems)) {
            $tasksItems = rand(1, 10);
        }

        for ($i = 0; $i < $tasksItems; $i++) {
            $tempTypes = $types;
            shuffle($tempTypes);

            $tasks[] = [
                'order' => $i + 1,
                'type' => array_shift($tempTypes),
                'date' => $this->faker->date(),
                'package_content' => $this->faker->sentence(),
                'package_instruction' => $this->faker->sentence(),
                'address' => $this->faker->address(),
                'address_reference' => $this->faker->sentence(),
                'delivery_zone_id' => $deliveryZone->id,

                'contact_full_name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
                'contact_phone_code' => '+' . rand(1, 150),
                'contact_phone_number' => $this->faker->phoneNumber(),

                'contact_email' => $this->faker->email(),

                'payment_method_id' => $paymentMethod->id,

                'total_receivable' => rand(50, 150),
            ];
        }

        return [
            'customer_account_id' => $customerAccount->id,
            'vehicle_id' => $vehicle->id,

            'tasks' => $tasks
        ];
    }

    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'POST',
            '/v1/shipments',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
