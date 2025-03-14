<?php

namespace Modules\Customers\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customers\src\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Customer::create([
            'customer_number' => 'CUST-001',
            'name' => 'John Doe',
            'company' => 'Doe Industries',
            'email' => 'johndoe@example.com'
        ]);

        Customer::create([
            'customer_number' => 'CUST-002',
            'name' => 'Jane Smith',
            'company' => null,
            'email' => 'janesmith@example.com'
        ]);
    }
}