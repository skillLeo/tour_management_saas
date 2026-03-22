<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('customers');

        Customer::query()->truncate();
        Address::query()->truncate();

        $names = ['John Smith', 'Jane Doe', 'Robert Johnson', 'Emily Davis', 'Michael Wilson', 'Sarah Brown', 'David Miller', 'Jessica Taylor', 'James Anderson', 'Amanda Thomas'];
        $phones = ['+11234567890', '+12345678901', '+13456789012', '+14567890123', '+15678901234', '+16789012345', '+17890123456', '+18901234567', '+19012345678', '+10123456789'];
        $countries = ['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'JP', 'KR', 'SG', 'NZ'];
        $states = ['California', 'Texas', 'Florida', 'New York', 'Illinois', 'Pennsylvania', 'Ohio', 'Georgia', 'Michigan', 'Washington'];
        $cities = ['Los Angeles', 'Houston', 'Miami', 'New York', 'Chicago', 'Philadelphia', 'Columbus', 'Atlanta', 'Detroit', 'Seattle'];
        $addresses = ['123 Main Street', '456 Oak Avenue', '789 Pine Road', '321 Maple Drive', '654 Cedar Lane', '987 Elm Street', '147 Birch Way', '258 Walnut Court', '369 Cherry Boulevard', '741 Willow Place'];
        $zipCodes = ['90001', '77001', '33101', '10001', '60601', '19101', '43201', '30301', '48201', '98101'];

        $customers = [
            'customer@botble.com',
            'vendor@botble.com',
        ];

        $index = 0;
        foreach ($customers as $item) {
            $customer = Customer::query()->create([
                'name' => $names[$index % count($names)],
                'email' => $item,
                'password' => Hash::make('12345678'),
                'phone' => $phones[$index % count($phones)],
                'avatar' => 'customers/' . rand(1, 10) . '.jpg',
                'dob' => Carbon::now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
            ]);

            $customer->confirmed_at = Carbon::now();
            $customer->save();

            Address::query()->create([
                'name' => $customer->name,
                'phone' => Arr::random($phones),
                'email' => $customer->email,
                'country' => Arr::random($countries),
                'state' => Arr::random($states),
                'city' => Arr::random($cities),
                'address' => Arr::random($addresses),
                'zip_code' => Arr::random($zipCodes),
                'customer_id' => $customer->id,
                'is_default' => true,
            ]);

            Address::query()->create([
                'name' => $customer->name,
                'phone' => Arr::random($phones),
                'email' => $customer->email,
                'country' => Arr::random($countries),
                'state' => Arr::random($states),
                'city' => Arr::random($cities),
                'address' => Arr::random($addresses),
                'zip_code' => Arr::random($zipCodes),
                'customer_id' => $customer->id,
                'is_default' => false,
            ]);

            $index++;
        }

        for ($i = 0; $i < 8; $i++) {
            $customer = Customer::query()->create([
                'name' => $names[$i % count($names)],
                'email' => 'customer' . ($i + 1) . '@example.test',
                'password' => Hash::make('12345678'),
                'phone' => $phones[$i % count($phones)],
                'avatar' => 'customers/' . ($i + 1) . '.jpg',
                'dob' => Carbon::now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
            ]);

            $customer->confirmed_at = Carbon::now();
            $customer->save();

            Address::query()->create([
                'name' => $customer->name,
                'phone' => Arr::random($phones),
                'email' => $customer->email,
                'country' => Arr::random($countries),
                'state' => Arr::random($states),
                'city' => Arr::random($cities),
                'address' => Arr::random($addresses),
                'zip_code' => Arr::random($zipCodes),
                'customer_id' => $customer->id,
                'is_default' => true,
            ]);
        }
    }
}
