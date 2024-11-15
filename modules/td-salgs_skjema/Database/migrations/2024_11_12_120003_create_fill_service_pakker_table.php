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
        // Opprett tabellen hvis den ikke finnes
        if (!Schema::hasTable('service_pakker')) {
            Schema::create('service_pakker', function (Blueprint $table) {
                $table->id(); // Primærnøkkel
                $table->string('name'); // Navn på tjenesten
                $table->text('description')->nullable(); // Beskrivelse
                $table->boolean('is_enabled')->default(true); // Om tjenesten er aktiv
                $table->timestamps(); // Opprettet/oppdatert tid
            });
        }

        // Data som skal legges til i tabellen
        $service_pakker = [
            [
                'name' => 'Serviceavtale - Bedrift - Next Cloud',
                'description' => "En serviceavtale basert på Nextcloud gir bedrifter en komplett løsning for skybasert lagring, filhåndtering og samarbeid, administrert av Trønder Data. Tjenesten inkluderer installasjon, vedlikehold, sikkerhetsoppdateringer og teknisk support for Nextcloud-servere, samt administrasjon av brukerkontoer og lagringskapasitet.",
                'is_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Serviceavtale - Bedrift - Office 365',
                'description' => "Denne serviceavtalen gir bedrifter en komplett løsning for IT-sikkerhet, produktivitet og support. Avtalen inkluderer en Office 365 Business Standard-lisens per bruker, sammen med avansert sikkerhetsbeskyttelse og administrerte tjenester for å sikre at alle enheter er oppdatert og beskyttet mot moderne trusler. Avtalen gir også tilgang til teknisk support og vedlikehold for en datamaskin per bruker.",
                'is_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Serviceavtale - Privat',
                'description' => "Vår serviceavtale gir deg trygghet og sikkerhet med en omfattende pakke av IT-tjenester, skreddersydd for privatkunder. Med denne avtalen får du alt du trenger for å holde datamaskinen din sikker og oppdatert, samt tilgang til teknisk support når du trenger det.",
                'is_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Sett inn eller oppdater rader i tabellen
        foreach ($service_pakker as $product) {
            DB::table('service_pakker')->updateOrInsert(
                ['name' => $product['name']], // Match etter navn
                $product // Sett inn eller oppdater
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Slett tabellen hvis den finnes
        Schema::dropIfExists('service_pakker');
    }
};
