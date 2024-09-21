<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTimeSpendsTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_time_spends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_reply_id')->nullable();
            $table->unsignedBigInteger('ticket_id'); // Referanse til ticket
            $table->unsignedBigInteger('time_rate_id')->nullable(); // Referanse til time_rate
            $table->integer('time_spend')->nullable(); // Tid brukt i minutter
            $table->timestamps();

            // Utenlandske nøkler
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('time_rate_id')->references('id')->on('time_rates')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_time_spends');
    }
}
