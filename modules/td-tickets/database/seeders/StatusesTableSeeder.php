<?php

namespace tronderdata\TdTickets\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tickets_status')->insert([
            [
                'name' => 'Open',
                'description' => 'Ticket is open and waiting to be handled.',
                'color' => '#ff0000',
                'is_default' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'In Progress',
                'description' => 'Ticket is currently being worked on.',
                'color' => '#ffff00',
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Closed',
                'description' => 'Ticket has been resolved and closed.',
                'color' => '#00ff00',
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
