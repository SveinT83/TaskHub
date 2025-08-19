<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTasksTable extends Migration
{
    public function up()
    {
        // Sjekk om tasks tabellen allerede finnes
        if (!Schema::hasTable('tasks')) {
            // Opprett tasks tabellen hvis den ikke finnes
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('due_date')->nullable();
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('child_task_id')->nullable()->constrained('tasks')->nullOnDelete();
                $table->foreignId('status_id')->nullable()->constrained('task_statuses')->nullOnDelete();
                $table->unsignedInteger('category_id')->nullable(); // Endret category_id til en INT med 5 tegn
                $table->foreignId('assigned_to')->nullable(); // Legger til assigned_to kolonne
                $table->unsignedInteger('wall_id', 5)->nullable(); // Legger til wall_id kolonne
                $table->integer('estimated_time')->nullable();
                $table->integer('actual_time')->nullable();
                $table->timestamps();
            });
        } else {
            // Hvis tasks tabellen finnes, sjekk om kolonnene allerede er satt
            Schema::table('tasks', function (Blueprint $table) {
                if (!Schema::hasColumn('tasks', 'category_id')) {
                    $table->unsignedInteger('category_id')->nullable(); // Endret category_id til en INT med 5 tegn
                }
                if (!Schema::hasColumn('tasks', 'assigned_to')) {
                    $table->foreignId('assigned_to')->nullable(); // Legger til assigned_to kolonne
                }
                if (!Schema::hasColumn('tasks', 'wall_id')) {
                    $table->unsignedInteger('wall_id', 5)->nullable(); // Legger til wall_id kolonne
                }
            });
        }
    }

    public function down()
    {
        // Hvis tasks-tabellen eksisterer, fjern kolonnene først
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                if (Schema::hasColumn('tasks', 'category_id')) {
                    $table->dropColumn('category_id'); // Dropper kolonnen
                }
                if (Schema::hasColumn('tasks', 'assigned_to')) {
                    $table->dropColumn('assigned_to'); // Dropper kolonnen
                }
                if (Schema::hasColumn('tasks', 'wall_id')) {
                    $table->dropColumn('wall_id'); // Dropper kolonnen
                }
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
