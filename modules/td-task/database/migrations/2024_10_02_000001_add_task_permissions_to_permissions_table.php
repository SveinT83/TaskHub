<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTaskPermissionsToPermissionsTable extends Migration
{
    public function up()
    {
        // Sjekk om tabellen 'permissions' eksisterer
        if (!Schema::hasTable('permissions')) {
            // Opprett tabellen hvis den ikke eksisterer
            Schema::create('permissions', function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('guard_name');
                $table->timestamps();
            });

            // Legg til standard task-relaterte tillatelser
            DB::table('permissions')->insert($this->getTaskPermissions());
        } else {
            // Sjekk om task-tillatelsene finnes, hvis ikke, legg de til
            foreach ($this->getTaskPermissions() as $permission) {
                if (!DB::table('permissions')->where('name', $permission['name'])->exists()) {
                    DB::table('permissions')->insert($permission);
                }
            }
        }
    }

    public function down()
    {
        // Fjern alle task-tillatelser
        DB::table('permissions')->whereIn('name', [
            'task.view', 'task.create', 'task.edit', 'task.delete', 'task.admin'
        ])->delete();
    }

    /**
     * Returnerer et array med task-relaterte tillatelser.
     */
    private function getTaskPermissions()
    {
        return [
            ['name' => 'task.view', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'task.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'task.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'task.delete', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'task.admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];
    }
}
