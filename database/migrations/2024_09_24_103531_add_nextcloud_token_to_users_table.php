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
        Schema::table('users', function (Blueprint $table) {
            // Legg til kolonne for Nextcloud token
            $table->string('nextcloud_token')->nullable()->after('password'); // Sørg for at kolonnen er valgfri (nullable)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Fjern kolonnen ved behov
            $table->dropColumn('nextcloud_token');
        });
    }
};
