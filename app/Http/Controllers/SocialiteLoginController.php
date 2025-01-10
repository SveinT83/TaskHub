<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialController extends Controller
{
    // Redirect to Facebook's OAuth page
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Handle the callback from Facebook and store the token
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        // Store the user access token in the database
        $user->facebook_access_token = $user->token;
        $user->save();

        // Redirect or return response
        return redirect('/home');
    }
}
