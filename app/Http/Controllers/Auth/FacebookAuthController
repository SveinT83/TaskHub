<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FacebookAuthController extends Controller
{
    // Redirect to Facebook for authentication
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Handle Facebook callback and store user info
    public function handleCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // Find or create user based on Facebook ID
            $user = User::where('facebook_id', $facebookUser->getId())->first();

            if (!$user) {
                $user = User::create([
                    'facebook_id' => $facebookUser->getId(),
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'avatar' => $facebookUser->getAvatar(),
                    // Store additional fields as needed
                ]);
            }

            // Log the user in
            Auth::login($user);

            return redirect()->route('home'); // Redirect to home or dashboard after login
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Could not authenticate with Facebook.');
        }
    }
}
