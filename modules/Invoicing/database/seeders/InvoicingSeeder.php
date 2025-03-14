<?php

namespace Modules\Invoicing\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoicingSeeder extends Seeder
{
    public function run()
    {
        DB::table('invoices')->insert([
            [
                'customer_id' => 1,
                'total' => 1000,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 2,
                'total' => 1500,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}