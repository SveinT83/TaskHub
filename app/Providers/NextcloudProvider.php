<?php

namespace App\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class NextcloudProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getBaseUrl() . '/apps/oauth2/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->getBaseUrl() . '/apps/oauth2/api/v1/token';
    }

    /**
     * Get the user by the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getBaseUrl() . '/ocs/v2.php/cloud/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'OCS-APIRequest' => 'true',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['ocs']['data'];
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'name' => $user['displayname'],
            'email' => $user['email'],
        ]);
    }

    /**
     * Get the base URL for the provider.
     *
     * @return string
     */
    protected function getBaseUrl()
    {
        return config('services.nextcloud.base_url');
    }
}
