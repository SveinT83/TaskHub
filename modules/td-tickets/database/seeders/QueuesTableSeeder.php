<?php

namespace tronderdata\TdTickets\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QueuesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tickets_queues')->insert([
            [
                'name' => 'Support',
                'description' => 'General support queue.',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales',
                'description' => 'Sales-related inquiries.',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
