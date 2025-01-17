<?php

namespace Modules\FacebookPostingModule\Services;

use FacebookAds\Api; // Import the Meta Business SDK

class FacebookApiService
{
    protected $api;

    /**
     * Initialize the Meta Business SDK with app credentials.
     */
    public function __construct()
    {
        $appId = config('facebookposter.facebook_api.app_id');
        $appSecret = config('facebookposter.facebook_api.app_secret');
        $accessToken = config('facebookposter.facebook_api.default_access_token');

        Api::init($appId, $appSecret, $accessToken);
        $this->api = Api::instance();
    }

    /**
     * Fetch a list of Facebook groups the user is a member of.
     */
    public function getUserGroups()
    {
        try {
            $response = $this->api->call('/me/groups', 'GET');
            return $response->getContent();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post a message to a specific Facebook group.
     */
    public function postToGroup($groupId, $message)
    {
        try {
            $params = ['message' => $message];
            $response = $this->api->call("/{$groupId}/feed", 'POST', $params);
            return $response->getContent();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post a message to a specific Facebook page.
     */
    public function postToPage($pageId, $message)
    {
        try {
            $params = ['message' => $message];
            $response = $this->api->call("/{$pageId}/feed", 'POST', $params);
            return $response->getContent();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
