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

            // Ny kolonne for priority_id
            $table->unsignedBigInteger('priority_id')->nullable(); // Legger til priority_id

            $table->dateTime('due_date')->nullable(); // Forfallsdato

            // Ny kolonne for ticket_category_id
            $table->unsignedBigInteger('ticket_category_id')->nullable(); // Legger til ticket_category_id

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->timestamps();

            // Indekser og fremmednøkler
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('status_id')->references('id')->on('tickets_status');
            $table->foreign('queue_id')->references('id')->on('tickets_queues');
            $table->foreign('assigned_to')->references('id')->on('users');

            // Fremmednøkler for de nye feltene
            $table->foreign('ticket_category_id')->references('id')->on('ticket_categories')->onDelete('set null');
            $table->foreign('priority_id')->references('id')->on('ticket_priorities')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
