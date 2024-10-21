<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    public function up()
    {
        // Sjekk om tabellen allerede finnes
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content');
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('status')->default('draft'); // draft, published, archived
                $table->unsignedBigInteger('user_id'); // Legger til user_id kolonnen
                $table->timestamps();

                // Fremmednøkkelreferanse til users tabellen
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Sjekk om tabellen eksisterer før sletting
        if (Schema::hasTable('articles')) {
            Schema::dropIfExists('articles');
        }
    }
}
