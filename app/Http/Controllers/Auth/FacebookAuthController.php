<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;  // Assuming you are storing Facebook data in the User model

class FacebookAuthController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $existingUser = User::where('facebook_id', $user->getId())->first();

            if ($existingUser) {
                auth()->login($existingUser);
            } else {
                $newUser = User::create([
                    'facebook_id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'facebook_token' => $user->token, // Save the token for future requests
                ]);
                auth()->login($newUser);
            }

            return redirect()->route('facebook.post-form');  // Redirect to the post form after login
        } catch (\Exception $e) {
            return redirect()->route('facebook.login')->with('error', 'Failed to login with Facebook.');
        }
    }
}
