<?php

namespace Modules\FacebookPostingModule\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookPostsTable extends Migration
{
    public function up()
    {
        Schema::create('facebook_posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facebook_posts');
    }
}
