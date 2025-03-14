<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AuditLogs\Database\Seeders\AuditLogSeeder;
use Modules\Customers\Database\Seeders\CustomerSeeder;
use Modules\Projects\Database\Seeders\ProjectSeeder;
use Modules\Inventory\Database\Seeders\InventorySeeder;
use Modules\Invoicing\Database\Seeders\InvoicingSeeder;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AuditLogSeeder::class,
            CustomerSeeder::class,
            ProjectSeeder::class,
            InventorySeeder::class,
        ]);
    }
}