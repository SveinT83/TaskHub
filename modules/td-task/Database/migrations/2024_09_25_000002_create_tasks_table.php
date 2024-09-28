<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('created_by')->constrained('users'); // Den som opprettet oppgaven
            $table->foreignId('child_task_id')->nullable()->constrained('tasks')->nullOnDelete(); // Underoppgaver
            $table->foreignId('status_id')->nullable()->constrained('task_statuses')->nullOnDelete(); // Status for oppgaven
            $table->foreignId('group_id')->nullable()->constrained('task_groups')->nullOnDelete(); // Oppgavegruppe
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Brukeren som oppgaven er tildelt
            $table->foreignId('wall_id')->nullable()->constrained('task_walls')->nullOnDelete(); // Hvilken vegg oppgaven tilhører
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}