<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id(); // Primærnøkkel
            $table->string('name'); // Navn på kontoen
            $table->text('description')->nullable(); // Beskrivelse (kan være null)
            $table->string('smtp_host'); // SMTP-vert
            $table->unsignedSmallInteger('smtp_port'); // SMTP-port (liten heltall)
            $table->string('smtp_encryption')->nullable(); // SMTP-kryptering (kan være null)
            $table->string('smtp_username'); // SMTP-brukernavn
            $table->text('smtp_password'); // Kryptert SMTP-passord
            $table->string('imap_host'); // IMAP-vert
            $table->unsignedSmallInteger('imap_port'); // IMAP-port (liten heltall)
            $table->string('imap_encryption')->nullable(); // IMAP-kryptering (kan være null)
            $table->string('imap_username'); // IMAP-brukernavn
            $table->text('imap_password'); // Kryptert IMAP-passord
            $table->boolean('is_default')->default(false); // Angir om dette er standardkontoen
            $table->timestamps(); // Oppretter `created_at` og `updated_at`
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_accounts'); // Sletter tabellen
    }
};