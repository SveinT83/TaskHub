<?php

namespace Modules\FacebookPostingModule\Tests;

use Tests\TestCase;

class FacebookPosterTest extends TestCase
{
    public function testListGroups()
    {
        $response = $this->get('/facebook-poster/groups');
        $response->assertStatus(200);
    }

    public function testPostToGroup()
    {
        $response = $this->post('/facebook-poster/post', [
            'group_id' => '123456',
            'message' => 'Test message',
        ]);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
