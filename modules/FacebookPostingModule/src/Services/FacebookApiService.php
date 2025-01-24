<?php

namespace Modules\FacebookPostingModule\Services;

use FacebookAds\Api;
use Illuminate\Support\Facades\Log;

class FacebookApiService
{
    protected $api;

    /**
     * FacebookApiService constructor.
     * @param string $accessToken The Facebook user access token.
     */
    public function __construct(string $accessToken)
    {
        $appId = config('facebookposter.facebook_api.app_id');
        $appSecret = config('facebookposter.facebook_api.app_secret');

        // Initialize the Facebook API with App credentials and user's access token
        Api::init($appId, $appSecret, $accessToken);
        $this->api = Api::instance();
    }

    /**
     * Fetch the user's Facebook groups.
     * @return array The list of user groups or an error response.
     */
    public function getUserGroups(): array
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
     * @param string $groupId The ID of the Facebook group.
     * @param string $message The message to post.
     * @return array The API response or an error response.
     */
    public function postToGroup(string $groupId, string $message): array
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
     * @param string $message The message to post.
     * @return array The API response or an error response.
     */
    public function postToWall(string $message): array
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
