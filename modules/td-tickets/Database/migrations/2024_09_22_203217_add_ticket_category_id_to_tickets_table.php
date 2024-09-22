<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Legg til 'ticket_category_id' som en nullable foreign key
            $table->unsignedBigInteger('ticket_category_id')->nullable()->after('queue_id');

            // Definer fremmednøkkelen
            $table->foreign('ticket_category_id')->references('id')->on('ticket_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Fjern fremmednøkkelen først
            $table->dropForeign(['ticket_category_id']);

            // Fjern kolonnen
            $table->dropColumn('ticket_category_id');
        });
    }
};
