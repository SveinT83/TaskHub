<?php

namespace Modules\CredentialsBank\Policies;

use App\Models\User;
use Modules\CredentialsBank\Models\CredentialsBank;

class CredentialsBankPolicy
{
    /**
     * Determine if the user can view the credential.
     */
    public function view(User $user, CredentialsBank $credential)
    {
        return $user->id === $credential->user_id;
    }

    /**
     * Determine if the user can update the credential.
     */
    public function update(User $user, CredentialsBank $credential)
    {
        return $user->id === $credential->user_id;
    }

    /**
     * Determine if the user can delete the credential.
     */
    public function delete(User $user, CredentialsBank $credential)
    {
        return $user->id === $credential->user_id;
    }
}