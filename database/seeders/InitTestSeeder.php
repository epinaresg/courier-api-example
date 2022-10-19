<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Models\DeliveryZone;
use App\Models\Driver;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Demo 1',
            'last_name' => 'KumaKloud',
            'email' => 'demo1@kumaKloud.com',
            'password' => Hash::make('12345678'),
			'group' => 1
        ]);
        User::create([
            'first_name' => 'Demo 2',
            'last_name' => 'KumaKloud',
            'email' => 'demo2@kumaKloud.com',
            'password' => Hash::make('12345678'),
			'group' => 2
        ]);
        User::create([
            'first_name' => 'Demo 3',
            'last_name' => 'KumaKloud',
            'email' => 'demo3@kumaKloud.com',
            'password' => Hash::make('12345678'),
			'group' => 3
        ]);

        Customer::factory()->count(20)->create();

        DeliveryZone::factory()->count(20)->create();

        PaymentMethod::factory()->count(5)->create();

        $vehicle = Vehicle::factory()->create();

        Driver::factory()->count(5)->create([
            'vehicle_id' => $vehicle->id,
        ]);

        $vehicle = Vehicle::factory()->create();

        Driver::factory()->count(5)->create([
            'vehicle_id' => $vehicle->id,
        ]);
    }
}
