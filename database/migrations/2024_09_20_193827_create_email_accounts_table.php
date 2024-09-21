<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Vennlig navn på e-postkontoen
            $table->string('description')->nullable(); // Beskrivelse av e-postkontoen

            // SMTP-innstillinger
            $table->string('smtp_host');
            $table->integer('smtp_port');
            $table->string('smtp_encryption')->nullable(); // 'tls', 'ssl', eller null
            $table->string('smtp_username');
            $table->string('smtp_password'); // Vurder å kryptere denne verdien

            // IMAP-innstillinger
            $table->string('imap_host');
            $table->integer('imap_port');
            $table->string('imap_encryption')->nullable(); // 'tls', 'ssl', eller null
            $table->string('imap_username');
            $table->string('imap_password'); // Vurder å kryptere denne verdien

            // Andre felt
            $table->boolean('is_default')->default(false); // Angir om dette er standard e-postkonto
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_accounts');
    }
}
