<?php

namespace Modules\FacebookPostingModule\test;

use Tests\TestCase;
use Modules\FacebookPostingModule\Models\FacebookPost;

class FacebookPosterTest extends TestCase
{
    public function testCreateFacebookPost()
    {
        $post = FacebookPost::create([
            'title' => 'Test Post',
            'content' => 'This is a test post.',
        ]);

        $this->assertDatabaseHas('facebook_posts', [
            'title' => 'Test Post',
        ]);
    }
}
