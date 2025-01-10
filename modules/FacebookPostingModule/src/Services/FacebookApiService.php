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
        // Load app credentials from the configuration
        $appId = config('facebookposter.facebook_api.app_id');
        $appSecret = config('facebookposter.facebook_api.app_secret');
        $accessToken = config('facebookposter.facebook_api.default_access_token');

        // Initialize the SDK and set up the API instance
        Api::init($appId, $appSecret, $accessToken);
        $this->api = Api::instance();
    }

    /**
     * Fetch a list of Facebook groups the user is a member of.
     */
    public function getUserGroups()
    {
        try {
            // Make a GET request to the "/me/groups" endpoint
            $response = $this->api->call('/me/groups', 'GET');
            return $response->getContent(); // Return the decoded response body
        } catch (\Exception $e) {
            // Handle errors and return the error message
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post a message to a specific Facebook group.
     *
     * @param string $groupId The ID of the group.
     * @param string $message The message to post.
     */
    public function postToGroup($groupId, $message)
    {
        try {
            $params = ['message' => $message]; // Parameters for the API request
            $response = $this->api->call("/{$groupId}/feed", 'POST', $params);
            return $response->getContent(); // Return the decoded response body
        } catch (\Exception $e) {
            // Handle errors and return the error message
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post a message to a specific Facebook page.
     *
     * @param string $pageId The ID of the page.
     * @param string $message The message to post.
     */
    public function postToPage($pageId, $message)
    {
        try {
            $params = ['message' => $message]; // Parameters for the API request
            $response = $this->api->call("/{$pageId}/feed", 'POST', $params);
            return $response->getContent(); // Return the decoded response body
        } catch (\Exception $e) {
            // Handle errors and return the error message
            return ['error' => $e->getMessage()];
        }
    }
}
