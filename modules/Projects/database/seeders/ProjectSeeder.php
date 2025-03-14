<?php

namespace Modules\Projects\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Projects\src\Models\Project;
use Modules\Customers\src\Models\Customer;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Project::create([
            'project_number' => 'P-20240301-ABCD',
            'customer_id' => 1,
            'status' => 'pending'
        ]);
    }
}