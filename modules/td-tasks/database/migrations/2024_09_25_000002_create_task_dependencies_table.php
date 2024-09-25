<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskDependenciesTable extends Migration
{
    public function up()
    {
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks');
            $table->foreignId('dependent_task_id')->constrained('tasks');
            $table->boolean('block_on_start')->default(false);
            $table->boolean('block_on_complete')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_dependencies');
    }
}
