<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionsForClientsModule extends Migration
{
    public function up()
    {
        // Opprett tillatelser
        $permissions = [
            'client.view',
            'client.create',
            'client.edit',
            'client.delete',
            'site.view',
            'site.create',
            'site.edit',
            'site.delete',
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Opprett tillatelse for moduladministrasjon
        Permission::create(['name' => 'module.admin']);

        // Opprett roller og tildel tillatelser
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $technicianRole = Role::firstOrCreate(['name' => 'Technician']);

        // Tildel alle tillatelser til Admin-rollen
        $adminRole->givePermissionTo(Permission::all());

        // Tildel spesifikke tillatelser til Technician-rollen (eksempel)
        $technicianPermissions = [
            'client.view',
            'site.view',
            'user.view',
        ];
        $technicianRole->givePermissionTo($technicianPermissions);
    }

    public function down()
    {
        // Fjern alle opprettede tillatelser ved rollback
        $permissions = [
            'client.view',
            'client.create',
            'client.edit',
            'client.delete',
            'site.view',
            'site.create',
            'site.edit',
            'site.delete',
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'module.admin',
        ];

        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }

        // Fjern roller om ønskelig
        Role::where('name', 'Admin')->delete();
        Role::where('name', 'Technician')->delete();
    }
}
