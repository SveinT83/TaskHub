<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\src\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run()
    {
        Inventory::create([
            'part_number' => 'PART-001',
            'name' => 'Replacement Screen',
            'stock_quantity' => 10,
            'min_stock_alert' => 3
        ]);
    }
}