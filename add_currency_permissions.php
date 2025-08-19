<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';

// Currency permissions to add
$currencyPermissions = [
    ['name' => 'currency.view', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'currency.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'currency.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'currency.delete', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
];

foreach ($currencyPermissions as $permission) {
    $exists = DB::table('permissions')->where('name', $permission['name'])->exists();
    if (!$exists) {
        DB::table('permissions')->insert($permission);
        echo "Added permission: {$permission['name']}\n";
    } else {
        echo "Permission already exists: {$permission['name']}\n";
    }
}

// Assign permissions to superadmin role
$superadminRole = DB::table('roles')->where('name', 'superadmin')->first();

if ($superadminRole) {
    $permissionIds = DB::table('permissions')
        ->whereIn('name', ['currency.view', 'currency.create', 'currency.edit', 'currency.delete'])
        ->pluck('id');

    foreach ($permissionIds as $permissionId) {
        $exists = DB::table('role_has_permissions')
            ->where('role_id', $superadminRole->id)
            ->where('permission_id', $permissionId)
            ->exists();

        if (!$exists) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $superadminRole->id,
                'permission_id' => $permissionId,
            ]);
            echo "Assigned permission ID {$permissionId} to superadmin role\n";
        } else {
            echo "Permission ID {$permissionId} already assigned to superadmin role\n";
        }
    }
}

echo "Currency permissions setup completed!\n";
