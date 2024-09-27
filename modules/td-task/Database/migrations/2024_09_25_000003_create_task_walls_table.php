<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskWallsTable extends Migration
{
    public function up()
    {
        Schema::create('task_walls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            
            // Bruker som opprettet task wall
            $table->foreignId('created_by')->constrained('users');
            
            // Bruker som task wall er assignet til
            $table->foreignId('assigned_to')->nullable()->constrained('users');  // Kan være null
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_walls');
    }
}
