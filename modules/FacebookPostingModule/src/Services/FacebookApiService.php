<?php

namespace Modules\FacebookPostingModule\Services;

use Facebook\Facebook;

class FacebookApiService
{
    /**
     * @var Facebook
     */
    protected $facebook;

    /**
     * FacebookApiService constructor.
     *
     * This constructor initializes the Facebook SDK with the app credentials and version.
     */
    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => config('facebookposter.facebook_api.app_id'),       // App ID from config
            'app_secret' => config('facebookposter.facebook_api.app_secret'), // App secret from config
            'default_graph_version' => 'v12.0',  // Default Graph API version
        ]);
    }

    /**
     * Get the groups the authenticated user belongs to.
     *
     * @return array
     */
    public function getUserGroups()
    {
        // Get the access token of the authenticated user
        $accessToken = auth()->user()->facebook_access_token;

        try {
            // Fetch the user's groups from the Facebook Graph API
            $response = $this->facebook->get('/me/groups', $accessToken);
            return $response->getDecodedBody();
        } catch (\Exception $e) {
            // Return error if something goes wrong
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Post a message to a Facebook group.
     *
     * @param string $groupId
     * @param string $message
     * @return array
     */
    public function postToGroup($groupId, $message)
    {
        // Get the access token of the authenticated user
        $accessToken = auth()->user()->facebook_access_token;

        try {
            // Post the message to the Facebook group using the Graph API
            $response = $this->facebook->post(
                "/{$groupId}/feed",
                ['message' => $message],
                $accessToken
            );
            return $response->getDecodedBody();
        } catch (\Exception $e) {
            // Return error if something goes wrong
            return ['error' => $e->getMessage()];
        }
    }
}
