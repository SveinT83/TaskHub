<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketRepliesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id'); // Referanse til ticket
            $table->unsignedBigInteger('user_id')->nullable(); // Brukeren som svarte (kan være null hvis kunden svarer)
            $table->text('message'); // Selve svaret
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_sent')->default(false); // Om e-posten er sendt eller ikke
            $table->timestamps();

            // Utenlandske nøkler
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_replies');
    }
}
