<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskWallsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('task_walls')) {
            Schema::create('task_walls', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('created_by')->constrained('users');
                $table->boolean('template')->default(false); // Ny kolonne for template
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('task_walls');
    }
}
