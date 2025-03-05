
<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKbMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if 'kb' menu exists in 'menus' table, if not, insert it
        $kbMenu = DB::table('menus')->where('slug', 'kb')->first();
        
        if (!$kbMenu) {
            $kbMenuId = DB::table('menus')->insertGetId([
                'name' => 'KbArtickles',
                'slug' => 'kb',
                'url' => '/kb',
                'description' => 'Knowledge base articles menu',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $kbMenuId = $kbMenu->id;
        }

        // Now add menu items if they don't exist
        $menuItems = [
            [
                'menu_id' => $kbMenuId,
                'title' => 'Artickles',
                'url' => '/kb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $kbMenuId,
                'title' => 'New Artickle',
                'url' => '/kb/article-form',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($menuItems as $item) {
            $exists = DB::table('menu_items')->where('url', $item['url'])->exists();
            if (!$exists) {
                DB::table('menu_items')->insert($item);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionally, you can delete the inserted menu items on rollback
        DB::table('menu_items')->where('url', '/kb/article-form')->delete();
        DB::table('menus')->where('slug', 'kb')->delete();
    }
}
?>

