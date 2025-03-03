<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddKbPermissionsAndRoles extends Migration
{
    public function up()
    {
        // Opprett de nødvendige tillatelsene
        $permissions = [
            'kb.admin',
            'kb.create',
            'kb.edit',
            'kb.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Opprett rollen "kbArthur" og tildel alle tillatelsene til den rollen
        $kbArthurRole = Role::firstOrCreate(['name' => 'kbArthur']);
        $kbArthurRole->givePermissionTo($permissions);

        // Legg til "kb.admin" tillatelsen til rollen "admin"
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo('kb.admin');
        }
    }

    public function down()
    {
        // Fjern tillatelsene og rollen som ble opprettet i opp-funksjonen
        $permissions = [
            'kb.admin',
            'kb.create',
            'kb.edit',
            'kb.view',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $permission->delete();
            }
        }

        $kbArthurRole = Role::where('name', 'kbArthur')->first();
        if ($kbArthurRole) {
            $kbArthurRole->delete();
        }
    }
}
