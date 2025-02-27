<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddEquipmentPermissions extends Migration
{
    public function up()
    {
        // Definer rettighetene for utstyrsmodulen
        $permissions = ['equipment.create', 'equipment.view', 'equipment.edit', 'equipment.delete', 'equipment.admin'];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Finn rollen "admin"
        $adminRole = Role::where('name', 'admin')->first();

        // Gi admin-rollen alle utstyrsrettighetene
        if ($adminRole) {
            foreach ($permissions as $permission) {
                if (!$adminRole->hasPermissionTo($permission)) {
                    $adminRole->givePermissionTo($permission);
                }
            }
        }
    }

    public function down()
    {
        // Fjern rettighetene ved nedgradering av migrasjonen
        $permissions = ['equipment.create', 'equipment.view', 'equipment.edit', 'equipment.delete', 'equipment.admin'];

        foreach ($permissions as $permission) {
            $permissionModel = Permission::where('name', $permission)->first();
            if ($permissionModel) {
                $permissionModel->delete();
            }
        }
    }
}
