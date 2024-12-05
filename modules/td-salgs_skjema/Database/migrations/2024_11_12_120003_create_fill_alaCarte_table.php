
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
        if (!Schema::hasTable('alacarte')) {
            Schema::create('alacarte', function (Blueprint $table) {
                $table->id(); // Primærnøkkel
                $table->string('product_number')->nullable(); // Produktnummer
                $table->string('name'); // Produktnavn
                $table->text('description')->nullable(); // Produktbeskrivelse
                $table->decimal('price', 8, 2)->default(0); // Produktpris
                $table->decimal('margine', 8, 2)->nullable(); // Margin
                $table->string('pr')->nullable(); // Enhetstype
                $table->boolean('private')->default(false); // Tilgjengelig for privatkunder
                $table->boolean('business')->default(false); // Tilgjengelig for bedriftskunder
                $table->string('timebank')->nullable(); // Tilleggsfelt
                $table->timestamps(); // Opprettet/oppdatert tidsstempel
            });
        }

        // Insert or update rows based on product name
        $products = [
            [
                'product_number' => null,
                'name' => 'Managed Antivirus',
                'description' => null,
                'price' => 47.2,
                'margine' => null,
                'pr' => 'enhet',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Managed EDR (Antivirus)',
                'description' => null,
                'price' => 68.2,
                'margine' => null,
                'pr' => 'enhet',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Managed Patch',
                'description' => null,
                'price' => 24.49,
                'margine' => null,
                'pr' => 'enhet',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Managed Mail',
                'description' => null,
                'price' => 71.2,
                'margine' => null,
                'pr' => 'stk',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Microsoft 365 Apps for Business',
                'description' => null,
                'price' => 225.0,
                'margine' => 43.8,
                'pr' => 'lisens',
                'timebank' => null,
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Microsoft 365 Business Basic',
                'description' => null,
                'price' => 150.0,
                'margine' => 49.81,
                'pr' => 'lisens',
                'timebank' => null,
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Microsoft 365 Business Standard',
                'description' => null,
                'price' => 275.0,
                'margine' => 55.6,
                'pr' => 'lisens',
                'timebank' => null,
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Microsoft 365 Business Premium',
                'description' => null,
                'price' => 450.0,
                'margine' => 70.68,
                'pr' => 'lisens',
                'timebank' => null,
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Microsoft 365 Business Standard EEA (uten Teams)',
                'description' => null,
                'price' => 225.0,
                'margine' => 43.8,
                'pr' => 'lisens',
                'timebank' => null,
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Exchange Online Archiving for Exchange Online',
                'description' => null,
                'price' => 75.0,
                'margine' => 20.52,
                'pr' => 'lisens',
                'timebank' => null,
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'NextCloud VM',
                'description' => null,
                'price' => 159.2,
                'margine' => 18.87,
                'pr' => 'vm',
                'timebank' => null,
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'NextCloud 100GB Addon',
                'description' => null,
                'price' => 20.0,
                'margine' => null,
                'pr' => 'stk',
                'private' => true,
                'business' => true,
                'timebank' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'NextCloud Bruker 10GB',
                'description' => null,
                'price' => 8.0,
                'margine' => 6.51,
                'pr' => 'bruker',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'NextCloud Bruker 100GB',
                'description' => null,
                'price' => 36.0,
                'margine' => 21.6,
                'pr' => 'bruker',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'UR Backup 100GB',
                'description' => null,
                'price' => 47.2,
                'margine' => 5.43,
                'pr' => 'enhet',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'UR Backup 250GB',
                'description' => null,
                'price' => 79.2,
                'margine' => 15.02,
                'pr' => 'enhet',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'UR Backup 500GB',
                'description' => null,
                'price' => 119.2,
                'margine' => 17.67,
                'pr' => 'enhet',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'UR Backup 1TB',
                'description' => null,
                'price' => 199.2,
                'margine' => 22.97,
                'pr' => 'enhet',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Web Hotell - Startpakke',
                'description' => null,
                'price' => 8.0,
                'margine' => null,
                'pr' => 'stk',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Web Hotell - Standardpakke',
                'description' => null,
                'price' => 79.2,
                'margine' => null,
                'pr' => 'stk',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Web Hotell - Proffpakke',
                'description' => null,
                'price' => 239.2,
                'margine' => null,
                'pr' => 'stk',
                'timebank' => null,
                'private' => true,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Managed Wordpress',
                'description' => null,
                'price' => 560.0,
                'margine' => null,
                'pr' => 'stk',
                'timebank' => '5.0',
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Timebank 12',
                'description' => null,
                'price' => 114.82,
                'margine' => null,
                'pr' => 'stk',
                'timebank' => '5.0',
                'private' => true,
                'business' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Timebank 05',
                'description' => null,
                'price' => 399.2,
                'margine' => 198.28,
                'pr' => 'stk',
                'timebank' => '5.0',
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Timebank 10',
                'description' => null,
                'price' => 680.0,
                'margine' => 278.16,
                'pr' => 'stk',
                'timebank' => '10.0',
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Timebank 20',
                'description' => null,
                'price' => 1160.0,
                'margine' => 356.32,
                'pr' => 'stk',
                'timebank' => '20.0',
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Timebank 30',
                'description' => null,
                'price' => 1900.0,
                'margine' => 694.48,
                'pr' => 'stk',
                'timebank' => '30.0',
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_number' => null,
                'name' => 'Timebank 40',
                'description' => null,
                'price' => 2532.0,
                'margine' => 924.64,
                'pr' => 'stk',
                'timebank' => '40.0',
                'private' => false,
                'business' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($products as $product) {
            DB::table('alacarte')->updateOrInsert(
                ['name' => $product['name']], // Match by name
                $product // Insert or update
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alacarte');
    }
};
