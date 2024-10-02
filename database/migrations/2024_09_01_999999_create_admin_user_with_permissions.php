<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sjekk om 'users' tabellen finnes før vi prøver å legge til kolonnen
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Legg til kolonne for Nextcloud token
                $table->string('nextcloud_token')->nullable()->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Fjern kolonnen ved behov
                $table->dropColumn('nextcloud_token');
            });
        }
    }
};
