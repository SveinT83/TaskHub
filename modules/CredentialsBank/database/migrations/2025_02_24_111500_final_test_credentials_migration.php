<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('credentials_bank', function (Blueprint $table) {
            $table->boolean('uses_individual_key')->default(false)->after('iv');
            $table->boolean('is_decrypted')->default(false)->after('uses_individual_key');
        });
    }

    public function down(): void
    {
        Schema::table('credentials_bank', function (Blueprint $table) {
            $table->dropColumn('is_decrypted');
            $table->dropColumn('uses_individual_key');
        });
    }
};