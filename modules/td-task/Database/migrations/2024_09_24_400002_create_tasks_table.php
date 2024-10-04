<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTasksTable extends Migration
{
    public function up()
    {
        // Først, sjekk om task_statuses tabellen finnes
        if (!Schema::hasTable('task_statuses')) {
            // Opprett task_statuses tabellen dersom den ikke eksisterer
            Schema::create('task_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('status_name');
                $table->timestamps();
            });

            // Legg inn standardrader i task_statuses
            DB::table('task_statuses')->insert([
                ['status_name' => 'Not Started', 'created_at' => now(), 'updated_at' => now()],
                ['status_name' => 'In Progress', 'created_at' => now(), 'updated_at' => now()],
                ['status_name' => 'Completed', 'created_at' => now(), 'updated_at' => now()],
                ['status_name' => 'Blocked', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Sjekk om tasks tabellen allerede finnes
        if (!Schema::hasTable('tasks')) {
            // Opprett tasks tabellen hvis den ikke finnes
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('due_date')->nullable();
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('child_task_id')->nullable()->constrained('tasks')->nullOnDelete(); // Underoppgaver
                $table->foreignId('status_id')->nullable()->constrained('task_statuses')->nullOnDelete(); // Status for oppgaven
                $table->foreignId('group_id')->nullable()->constrained('task_groups')->nullOnDelete(); // Oppgavegruppe
                $table->timestamps();
            });
        } else {
            // Hvis tasks tabellen finnes, sjekk om kolonnen status_id allerede er satt
            if (!Schema::hasColumn('tasks', 'status_id')) {
                // Hvis fremmednøkkelen ikke er satt, legger vi til
                Schema::table('tasks', function (Blueprint $table) {
                    $table->foreignId('status_id')->nullable()->constrained('task_statuses')->nullOnDelete(); // Status for oppgaven
                });
            }
        }
    }

    public function down()
    {
        // Hvis tasks-tabellen eksisterer, fjern fremmednøkkelen først
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                if (Schema::hasColumn('tasks', 'status_id')) {
                    $table->dropForeign(['status_id']); // Dropper fremmednøkkelen
                    $table->dropColumn('status_id'); // Dropper kolonnen
                }
            });

            // Slett tasks tabellen
            Schema::dropIfExists('tasks');
        }

        // Slett task_statuses tabellen
        Schema::dropIfExists('task_statuses');
    }
}
