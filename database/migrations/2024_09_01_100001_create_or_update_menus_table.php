<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrUpdateMenusTable extends Migration
{
    public function up()
    {
        // Sjekk om tabellen eksisterer
        if (!Schema::hasTable('menus')) {
            // Hvis den ikke eksisterer, opprett tabellen
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->index(); // Indeksert slug
                $table->string('url', 30)->nullable(); // Kan være null
                $table->string('description')->nullable(); // Kan være null
                $table->timestamps(); // Oppretter `created_at` og `updated_at`
            });
        } else {
            // Hvis tabellen eksisterer, sjekk og legg til manglende kolonner
            Schema::table('menus', function (Blueprint $table) {
                if (!Schema::hasColumn('menus', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('menus', 'slug')) {
                    $table->string('slug')->index(); // Indeksert slug
                }
                if (!Schema::hasColumn('menus', 'url')) {
                    $table->string('url', 30)->nullable(); // Kan være null
                }
                if (!Schema::hasColumn('menus', 'description')) {
                    $table->string('description')->nullable(); // Kan være null
                }
                if (!Schema::hasColumn('menus', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('menus', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
