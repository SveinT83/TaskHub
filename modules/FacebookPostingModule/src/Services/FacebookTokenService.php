<?php

namespace Modules\FacebookPostingModule\Services;

use Illuminate\Support\Facades\Cache;

class FacebookTokenService
{
    /**
     * Store the user's Facebook token securely.
     */
    public function storeToken($userId, $token)
    {
        Cache::put("facebook_token_{$userId}", $token, now()->addDays(60)); // Cache token for 60 days
    }

    /**
     * Retrieve the user's Facebook token.
     */
    public function getToken($userId)
    {
        return Cache::get("facebook_token_{$userId}");
    }
}
