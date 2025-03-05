<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddCategoryPermissions  extends Migration
{
    public function up()
    {
        // Opprett rettigheter hvis de ikke allerede eksisterer
        $permissions = ['category.create', 'category.admin'];
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Finn rollen "admin"
        $adminRole = Role::where('name', 'admin')->first();

        // Legg til "category.admin" til admin-rollen hvis den ikke allerede er lagt til
        if ($adminRole && !$adminRole->hasPermissionTo('category.admin')) {
            $adminRole->givePermissionTo('category.admin');
        }

        // Gi superadmin (ID 1) alle rettigheter for category modulen
        $superAdminRole = Role::find(1);
        if ($superAdminRole) {
            foreach ($permissions as $permission) {
                if (!$superAdminRole->hasPermissionTo($permission)) {
                    $superAdminRole->givePermissionTo($permission);
                }
            }
        }
    }

    public function down()
    {
        // Fjern rettighetene ved nedgradering av migrasjonen
        $permissions = ['category.create', 'category.admin'];
        foreach ($permissions as $permission) {
            $permissionModel = Permission::where('name', $permission)->first();
            if ($permissionModel) {
                $permissionModel->delete();
            }
        }
    }
}
