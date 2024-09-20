<?php

namespace tronderdata\TdTickets\database\seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            StatusesTableSeeder::class,
            QueuesTableSeeder::class,
        ]);
    }
}
