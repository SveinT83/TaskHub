<?php

namespace tronderdata\TdTickets\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            StatusesTableSeeder::class,
            QueuesTableSeeder::class,
            TimeRatesSeeder::class,
        ]);
    }
}
