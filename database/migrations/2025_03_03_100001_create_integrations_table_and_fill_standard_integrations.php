<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateIntegrationsTableAndFillStandardIntegrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('integrations')) {
            Schema::create('integrations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->boolean('active')->default(0);
                $table->timestamps();
            });

            // Insert default integrations
            DB::table('integrations')->insert([
                ['name' => 'nextcloud', 'active' => 0, 'icon' => 'i bi-cloud' ],
                ['name' => 'tripletex', 'active' => 0],
            ]);
        }

        if (!Schema::hasTable('integration_credentials')) {
            Schema::create('integration_credentials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('integration_id')->constrained('integrations');
                $table->string('api')->nullable();
                $table->string('clientid')->nullable();
                $table->string('clientsecret')->nullable();
                $table->string('redirecturi')->nullable();
                $table->string('baseurl')->nullable();
                $table->string('username')->nullable();
                $table->string('password')->nullable();
                $table->string('expires_in')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integration_credentials');
        Schema::dropIfExists('integrations');
    }
}
