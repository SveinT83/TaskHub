<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTaskStatusesTable extends Migration
{
    public function up()
    {
        // Først sjekk om tabellen finnes
        if (!Schema::hasTable('task_statuses')) {
            // Hvis tabellen ikke finnes, opprett den
            Schema::create('task_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('status_name');
                $table->timestamps();
            });

            // Fyll inn standardrader
            DB::table('task_statuses')->insert([
                ['status_name' => 'Not Started', 'created_at' => now(), 'updated_at' => now()],
                ['status_name' => 'In Progress', 'created_at' => now(), 'updated_at' => now()],
                ['status_name' => 'Completed', 'created_at' => now(), 'updated_at' => now()],
                ['status_name' => 'Blocked', 'created_at' => now(), 'updated_at' => now()],
            ]);

        } else {
            // Hvis tabellen finnes, sjekk om standardrader finnes
            $statuses = DB::table('task_statuses')->pluck('status_name')->toArray();

            // Hvis noen av standardradene mangler, sett dem inn
            $standardStatuses = ['Not Started', 'In Progress', 'Completed', 'Blocked'];

            foreach ($standardStatuses as $status) {
                if (!in_array($status, $statuses)) {
                    DB::table('task_statuses')->insert([
                        'status_name' => $status,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }

    public function down()
    {
        // Drop tabellen om den finnes
        Schema::dropIfExists('task_statuses');
    }
}
