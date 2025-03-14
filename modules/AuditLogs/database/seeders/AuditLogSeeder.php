<?php

namespace Modules\AuditLogs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AuditLogs\src\Models\AuditLog;
use Modules\Customers\src\Models\Customer;
use Modules\Projects\src\Models\Project;
use App\Models\User; // ✅ Use Laravel's default User model
use Illuminate\Support\Facades\Log;

class AuditLogSeeder extends Seeder
{
    public function run()
    {
        // Fetch existing data to prevent crashes
        $user = User::first();
        $customer = Customer::first();
        $project = Project::first();

        if (!$user) {
            Log::warning("AuditLogSeeder skipped: No users found in the database.");
            return; // ✅ Skip seeding if no users exist
        }

        if (!$customer) {
            Log::warning("AuditLogSeeder skipped: No customers found in the database.");
            return;
        }

        if (!$project) {
            Log::warning("AuditLogSeeder skipped: No projects found in the database.");
            return;
        }

        AuditLog::create([
            'user_id' => $user->id, // ✅ Now references App\Models\User
            'entity_type' => 'Customer',
            'entity_id' => $customer->id,
            'action' => 'created',
            'old_value' => null,
            'new_value' => json_encode($customer->toArray()),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'entity_type' => 'Project',
            'entity_id' => $project->id,
            'action' => 'updated',
            'old_value' => json_encode(['status' => 'pending']),
            'new_value' => json_encode(['status' => 'approved']),
        ]);

        Log::info("AuditLogSeeder: Successfully inserted audit logs.");
    }
}