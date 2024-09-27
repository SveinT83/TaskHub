<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sjekk om "ticket_categories" tabellen allerede finnes
        if (!Schema::hasTable('ticket_categories')) {
            Schema::create('ticket_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('is_default')->default(false);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();

                // Legg til utenlandske nøkler hvis du har en users-tabell
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            });

            // Valgfritt: Sett inn en standard kategori hvis nødvendig
            DB::table('ticket_categories')->insert([
                [
                    'name' => 'General',
                    'is_default' => true,
                    'created_by' => 1, // Anta at admin har ID 1
                    'updated_by' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Valgfritt: Legg til flere initial data eller relaterte menyinnstillinger her
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sjekk om "ticket_categories" tabellen finnes før den slettes
        if (Schema::hasTable('ticket_categories')) {
            Schema::dropIfExists('ticket_categories');
        }
    }
};
