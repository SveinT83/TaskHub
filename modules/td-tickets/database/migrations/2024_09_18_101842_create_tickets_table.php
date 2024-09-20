<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->unsignedBigInteger('client_id')->nullable(); // Kunde
            $table->unsignedBigInteger('status_id')->default(1); // Standardstatus (f.eks. Open)
            $table->unsignedBigInteger('queue_id')->nullable(); // Kø
            $table->unsignedBigInteger('assigned_to')->nullable(); // Tildelt tekniker

            $table->unsignedTinyInteger('priority')->default(3); // Prioritet (1=høy, 5=lav)

            $table->dateTime('due_date')->nullable(); // Forfallsdato

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->timestamps();

            // Indekser og fremmednøkler
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('status_id')->references('id')->on('tickets_status');
            $table->foreign('queue_id')->references('id')->on('tickets_queues');
            $table->foreign('assigned_to')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
