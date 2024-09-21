<?php

namespace tronderdata\TdTickets\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeRatesSeeder extends Seeder
{
    public function run()
    {
        DB::table('time_rates')->insert([
            [
                'name' => 'On Site',
                'price' => 1200.00,
                'taxable' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Remote Support',
                'price' => 520.00,
                'taxable' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Internal Work',
                'price' => 0.00,
                'taxable' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
