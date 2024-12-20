<?php
namespace Modules\FacebookPostingModule\src\Services;

use Facebook\Facebook;

class FacebookApiService
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => config('facebookposter.facebook_api.app_id'),
            'app_secret' => config('facebookposter.facebook_api.app_secret'),
            'default_graph_version' => 'v12.0',
        ]);
    }

    public function getUserGroups()
    {
        $accessToken = auth()->user()->facebook_access_token;

        try {
            $response = $this->facebook->get('/me/groups', $accessToken);
            return $response->getDecodedBody();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function postToGroup($groupId, $message)
    {
        $accessToken = auth()->user()->facebook_access_token;

        try {
            $response = $this->facebook->post(
                "/{$groupId}/feed",
                ['message' => $message],
                $accessToken
            );
            return $response->getDecodedBody();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
