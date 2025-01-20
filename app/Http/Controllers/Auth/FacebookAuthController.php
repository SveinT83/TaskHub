<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\FacebookPostingModule\Services\FacebookTokenService; // Import the token service
use App\Models\User;

class FacebookAuthController extends Controller
{
    protected $facebookTokenService;

    public function __construct(FacebookTokenService $facebookTokenService)
    {
        $this->facebookTokenService = $facebookTokenService;
    }

    /**
     * Redirect to Facebook OAuth page.
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->scopes([
            'public_profile',
            'email',
            'pages_manage_posts',
            'publish_to_groups'
        ])->redirect();
    }

    /**
     * Handle the Facebook OAuth callback and save the token.
     */
    public function handleCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            // Save token securely in the database using the token service
            $this->facebookTokenService->storeToken(auth()->id(), $user->token);

            // Associate user and redirect
            User::updateOrCreate(
                ['id' => auth()->id()],
                [
                    'facebook_id' => $user->id,
                    'facebook_token' => $user->token, // Optionally store for the current user
                ]
            );

            return redirect()->route('facebook.post-form')->with('success', 'Logged in successfully!');
        } catch (\Exception $e) {
            Log::error('Facebook login failed: ' . $e->getMessage());
            return redirect()->route('facebook.login')->with('error', 'Failed to login with Facebook.');
        }
    }

    /**
     * Exchange the short-lived token for a long-lived token.
     */
    public function exchangeToken(Request $request)
    {
        $validated = $request->validate(['accessToken' => 'required|string']);

        try {
            $response = Http::asForm()->get('https://graph.facebook.com/v21.0/oauth/access_token', [
                'grant_type' => 'fb_exchange_token',
                'client_id' => env('FACEBOOK_CLIENT_ID'),
                'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                'fb_exchange_token' => $validated['accessToken'],
            ]);

            if ($response->successful()) {
                $longLivedToken = $response->json()['access_token'];

                // Save the long-lived token securely using the service
                $this->facebookTokenService->storeToken(auth()->id(), $longLivedToken);

                return response()->json(['success' => true, 'token' => $longLivedToken]);
            }

            return response()->json(['success' => false, 'error' => 'Failed to exchange token']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
