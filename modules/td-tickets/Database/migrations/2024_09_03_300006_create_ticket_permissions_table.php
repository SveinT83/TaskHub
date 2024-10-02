<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role; // Legg til denne importen
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Definer tillatelser
        $permissions = [
            'tickets.view',
            'tickets.create',
            'tickets.edit',
            'tickets.delete', // Sørg for å inkludere alle tillatelser
            'tickets.admin',
        ];

        // Opprett tillatelser hvis de ikke allerede finnes
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Opprett roller og tildel tillatelser
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $technicianRole = Role::firstOrCreate(['name' => 'Technician']);

        // Tildel alle tillatelser til Admin-rollen
        $adminRole->syncPermissions(Permission::all());

        // Tildel spesifikke tillatelser til Technician-rollen
        $technicianPermissions = [
            'tickets.view',
            // Legg til flere tillatelser hvis nødvendig
        ];
        $technicianRole->syncPermissions($technicianPermissions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Definer tillatelser som skal fjernes
        $permissions = [
            'tickets.view',
            'tickets.create',
            'tickets.edit',
            'tickets.delete',
            'tickets.admin',
        ];

        // Fjern tillatelser
        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }

        // Fjern roller
        Role::where('name', 'Admin')->delete();
        Role::where('name', 'Technician')->delete();
    }
};
