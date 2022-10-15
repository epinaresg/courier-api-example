<?php

namespace Tests\Feature\Shipment;

use App\Models\Shipment;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $account;
    private $qty;
    private $shipments;

    private $jsonStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->account = $this->user->account;

        $this->qty = rand(10, 35);
        $this->tasks = [];

        for ($i = 0; $i < $this->qty; $i++) {
            $shipment = $this->createShipment($this->account);

            foreach ($shipment->tasks as $task) {
                $this->tasks[] = [
                    'shipment_id' => $task->shipment_id,
                    'customer_account_id' => $shipment->customer_account_id,
                    'date' => $task->date->format('Y-m-d'),
                    'state_id' => $task->state_id
                ];
            }
        }

        $this->jsonStructure = [
            'items' => [
                '*' => [
                    'id',
                    'customer',
                    'vehicle',
                    'tasks_qty',
                    'tasks_missing_qty',
                    'tasks_completed_qty',
                    'tasks_completed_percent',
                    'total_pick_up',
                    'total_drop_off',
                    'total'
                ]
            ],
            'pagination'
        ];
    }

    /** @test */
    public function without_authorization()
    {
        $response = $this->makeRequest();

        $response->assertStatus(401, $response->status());
    }

    /** @test */
    public function get_list()
    {
        $response = $this->makeRequest([], $this->user);

        $response->assertStatus(200, $response->status());
        $responseData = $response->decodeResponseJson();

        $response->assertJsonStructure($this->jsonStructure);

        $key = rand(0, count($responseData['items']) - 1);
        $shipmentId = $responseData['items'][$key]['id'];

        $shipment = Shipment::find($shipmentId);

        dd($responseData['items'][$key]);
        $this->assertEquals($responseData['items'][$key]['id'], $shipment->id);
        $this->assertEquals($responseData['items'][$key]['id'], $shipment->id);
        $this->assertEquals($responseData['items'][$key]['id'], $shipment->id);
        $this->assertEquals($responseData['items'][$key]['id'], $shipment->id);
        $this->assertEquals($responseData['items'][$key]['id'], $shipment->id);


        $this->assertEquals($responseData['pagination']['total'], $this->qty);
    }

    /** @test */
    public function get_list_filter_by_dates()
    {
        $initialDate = $this->tasks[rand(0, count($this->tasks) - 1)]['date'];
        $finalDate = Carbon::parse($initialDate)->addDays(rand(1, 5))->format('Y-m-d');

        $total = $this->tasks->whereBetween('date', [$initialDate, $finalDate])->pluck('shipment_id')->unique()->values()->count();

        $response = $this->makeRequest([
            'dates' => "{$initialDate},{$finalDate}"
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure($this->jsonStructure);

        $this->assertEquals($responseData['pagination']['total'], $total);
    }

    /** @test */
    public function get_list_filter_by_date()
    {
        $initialDate = $this->tasks[rand(0, count($this->tasks) - 1)]['date'];

        $total = $this->tasks->where('date', $initialDate)->pluck('shipment_id')->unique()->values()->count();

        $response = $this->makeRequest([
            'dates' => "{$initialDate}"
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure($this->jsonStructure);

        $this->assertEquals($responseData['pagination']['total'], $total);
    }

    /** @test */
    public function get_list_filter_by_state()
    {
        $stateId =  $this->tasks[rand(0, count($this->tasks) - 1)]['state_id'];

        $total = $this->tasks->where('state_id', $stateId)->pluck('shipment_id')->unique()->values()->count();

        $response = $this->makeRequest([
            'state_id' => $stateId
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure($this->jsonStructure);

        $this->assertEquals($responseData['pagination']['total'], $total);
    }

    /** @test */
    public function get_list_filter_by_term_customer_account()
    {
        $customerAccountId =  $this->tasks[rand(0, count($this->tasks) - 1)]['customer_account_id'];

        $total = $this->tasks->where('customer_account_id', $customerAccountId)->pluck('shipment_id')->unique()->values()->count();

        $response = $this->makeRequest([
            'customer_account_id' => $customerAccountId
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure($this->jsonStructure);

        $this->assertEquals($responseData['pagination']['total'], $total);
    }

    /** @test */
    public function get_list_all_filters_return_results()
    {
        $customerAccountId =  $this->tasks[rand(0, count($this->tasks) - 1)]['customer_account_id'];

        $segment1 = $this->tasks->where('customer_account_id', $customerAccountId);
        $segment2 = $segment1->values()->random(rand(1, count($segment1)));

        $dates = $segment2->sortBy('date')->pluck('date')->toArray();

        $initialDate = reset($dates);
        $finalDate = end($dates);

        $stateId = $segment2->sortBy('date')->pluck('state_id')->values()[0];

        $total = $segment2->where('state_id', $stateId)->pluck('shipment_id')->unique()->count();

        $response = $this->makeRequest([
            'customer_account_id' => $customerAccountId,
            'dates' => "{$initialDate},{$finalDate}",
            'state_id' => $stateId
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure($this->jsonStructure);

        $this->assertEquals($responseData['pagination']['total'], $total);
    }

    /** @test */
    public function get_list_all_filters_return_zero_results()
    {
        $response = $this->makeRequest([
            'customer_account_id' => 123,
            'dates' => '2020-10-10',
            'state_id' => 123
        ], $this->user);

        $responseData = $response->decodeResponseJson();

        $response->assertStatus(200, $response->status());

        $response->assertJsonStructure($this->jsonStructure);

        $this->assertEquals($responseData['pagination']['total'], 0);
    }


    private function makeRequest(array $data = [], User $user = null): TestResponse
    {
        if ($user) {
            Sanctum::actingAs($user);
        }

        return $this->json(
            'GET',
            '/v1/shipments',
            $data,
            [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ]
        );
    }
}
