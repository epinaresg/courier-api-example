<?php

namespace Tests;

use App\Models\Account;
use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Models\DeliveryZone;
use App\Models\Driver;
use App\Models\PaymentMethod;
use App\Models\Shipment;
use App\Models\State;
use App\Models\Task;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private $accountKey;
    private $tempCustomerAccount;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }

    protected function createUser(): User
    {
        $user = User::factory()->create();
        $account = Account::factory()->create();

        $user->account_id = $account->id;
        $user->save();

        return $user;
    }

    protected function createAccount(): Account
    {
        return Account::factory()->create();
    }

    protected function createCustomerAccount(Account $account = null): CustomerAccount
    {
        if (!$account) {
            $account = $this->createAccount();
        }

        $customer = Customer::factory()->create();

        return CustomerAccount::factory()->create([
            'customer_id' => $customer->id,
            'account_id' => $account->id,
            'email' => $customer->email
        ]);
    }

    protected function createVehicle(Account $account = null): Vehicle
    {
        if (!$account) {
            $account = $this->createAccount();
        }

        return Vehicle::factory()->create([
            'account_id' => $account->id,
        ]);
    }

    protected function createDeliveryZone(Account $account = null): DeliveryZone
    {
        if (!$account) {
            $account = $this->createAccount();
        }

        return DeliveryZone::factory()->create([
            'account_id' => $account->id,
        ]);
    }

    protected function createPaymentMethod(Account $account = null): PaymentMethod
    {
        if (!$account) {
            $account = $this->createAccount();
        }

        return PaymentMethod::factory()->create([
            'account_id' => $account->id,
        ]);
    }

    protected function createDriver(Account $account = null): Driver
    {
        if (!$account) {
            $account = $this->createAccount();
        }

        $vehicle = $this->createVehicle($account);

        return Driver::factory()->create([
            'account_id' => $account->id,
            'vehicle_id' => $vehicle->id,
        ]);
    }

    protected function createShipment(Account $account = null): Shipment
    {
        if (!$account) {
            $account = $this->createAccount();
        }

        if (rand(0, 1) === 1 && $this->tempCustomerAccount) {
            $customerAccount = $this->tempCustomerAccount;
        } else {
            $customerAccount = $this->createCustomerAccount($account);
            $this->tempCustomerAccount = $customerAccount;
        }


        $vehicle = $this->createVehicle($account);

        $shipment = Shipment::factory()->create([
            'account_id' => $account->id,
            'customer_account_id' => $customerAccount->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $paymentMethod = $this->createPaymentMethod($account);

        if (State::count() === 0) {
            Artisan::call('db:seed');
        }

        $state = State::inRandomOrder()->first();

        for ($i = 0; $i < rand(2, 10); $i++) {
            $deliveryZone = $this->createDeliveryZone($account);

            Task::factory()->create([
                'order' => $i + 1,
                'state_id' => $state->id,
                'account_id' => $account->id,
                'shipment_id' => $shipment->id,
                'delivery_zone_id' => $deliveryZone->id,
                'payment_method_id' => $paymentMethod->id,
            ]);
        }

        return $shipment;
    }

    protected function setAccountKey(string $accountKey): void
    {
        $this->accountKey = $accountKey;
    }

    protected function getAccountKey(): ?string
    {
        return $this->accountKey;
    }
}
