<?php

namespace Modules\FacebookPostingModule\Services;

use FacebookAds\Api;
use Illuminate\Support\Facades\Log;

class FacebookApiService
{
    protected $api;

    public function __construct($accessToken)
    {
        $appId = config('facebookposter.facebook_api.app_id');
        $appSecret = config('facebookposter.facebook_api.app_secret');

        Api::init($appId, $appSecret, $accessToken);
        $this->api = Api::instance();
    }

    /**
     * Fetch the user's Facebook groups.
     */
    public function getUserGroups()
    {
        try {
            $response = $this->api->call('/me/groups', 'GET');
            return $response->getContent();
        } catch (\Exception $e) {
            Log::error('Failed to fetch user groups: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post to a Facebook group.
     */
    public function postToGroup($groupId, $message)
    {
        try {
            $params = ['message' => $message];
            $response = $this->api->call("/{$groupId}/feed", 'POST', $params);
            return $response->getContent();
        } catch (\Exception $e) {
            Log::error('Failed to post to group: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post to a Facebook wall.
     */
    public function postToWall($message)
    {
        try {
            $params = ['message' => $message];
            $response = $this->api->call('/me/feed', 'POST', $params);
            return $response->getContent();
        } catch (\Exception $e) {
            Log::error('Failed to post to wall: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
