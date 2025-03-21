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

        // Gi superadmin (ID 1) alle rettigheter som standard
        $superAdminRole = Role::find(1);
        if ($superAdminRole) {
            foreach ($permissions as $permission) {
                if (!$superAdminRole->hasPermissionTo($permission)) {
                    $superAdminRole->givePermissionTo($permission);
                }
            }
        }

        // Opprett nye roller for equipment-modulen
        $viewRole = Role::firstOrCreate(['name' => 'equipmentView']);
        $equipmentUserRole = Role::firstOrCreate(['name' => 'equipmentUser']);

        // Gi view-rollen alle view-rettigheter
        if ($viewRole && !$viewRole->hasPermissionTo('equipment.view')) {
            $viewRole->givePermissionTo('equipment.view');
        }

        // Gi equipmentUser-rollen alle rettigheter
        if ($equipmentUserRole) {
            foreach ($permissions as $permission) {
                if (!$equipmentUserRole->hasPermissionTo($permission)) {
                    $equipmentUserRole->givePermissionTo($permission);
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

        // Fjern de nye rollene
        Role::where('name', 'equipmentView')->delete();
        Role::where('name', 'equipmentUser')->delete();
    }
}
