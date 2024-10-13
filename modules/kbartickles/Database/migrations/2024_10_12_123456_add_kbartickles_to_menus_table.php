<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddKbarticklesToMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sjekk om raden allerede eksisterer
        $menuExists = DB::table('menus')->where('slug', 'kb')->exists();

        // Hvis raden ikke eksisterer, opprett den
        if (!$menuExists) {
            DB::table('menus')->insert([
                'name' => 'KbArtickles',
                'slug' => 'kb',
                'url' => '/kb',
                'description' => '?',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Fjern raden hvis du ønsker å rulle tilbake migrasjonen
        DB::table('menus')->where('slug', 'kb')->delete();
    }
}
