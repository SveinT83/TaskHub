<?php

namespace App\Http\Controllers;

use Modules\Facebookpostingmodule\src\Services\FacebookService;
use Illuminate\Http\Request;

class FacebookController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function login()
    {
        $helper = $this->facebookService->getRedirectLoginHelper();
        $permissions = ['publish_actions']; // Permissions to post on user's behalf
        $loginUrl = $helper->getLoginUrl(config('facebook.redirect_url'), $permissions);

        return redirect()->to($loginUrl);
    }

    public function callback(Request $request)
    {
        $helper = $this->facebookService->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
            if (isset($accessToken)) {
                session(['facebook_access_token' => $accessToken]);
                return redirect()->route('facebook.post');
            }
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function post(Request $request)
    {
        $accessToken = session('facebook_access_token');
        if (!$accessToken) {
            return redirect()->route('facebook.login');
        }

        $message = $request->input('message', 'Hello, this is a test post from Laravel!');
        $result = $this->facebookService->postToFacebook($accessToken, $message);

        return response()->json($result);
    }
}
