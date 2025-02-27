<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - NEXTCLOUD CONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling Nextcloud integration related actions such as redirecting the user to Nextcloud OAuth2 authorization page,
// handling the callback from Nextcloud after user authorization, showing the Nextcloud integration settings, and toggling the Nextcloud integration status.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Integrations\Nextcloud;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// --------------------------------------------------------------------------------------------------
// MODELL - MENU
// --------------------------------------------------------------------------------------------------
// This controller uses the Menu model to manage and manipulate menu data for the Nextcloud integration in the admin panel.
// --------------------------------------------------------------------------------------------------
use App\Models\Menu;

// --------------------------------------------------------------------------------------------------
// MODELL - SETTINGS
// --------------------------------------------------------------------------------------------------
// This controller uses the Settings model to manage and manipulate settings data for the Nextcloud integration in the admin panel.
// --------------------------------------------------------------------------------------------------
use App\Models\Setting;

class NextcloudController extends Controller
{

    // -------------------------------------------------
    // Nextcloud integration settings
    // -------------------------------------------------
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $baseUrl;



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CONSTRUCTOR
    // --------------------------------------------------------------------------------------------------
    // The constructor initializes the Nextcloud integration settings such as client ID, client secret, redirect URI, and base URL.
    // --------------------------------------------------------------------------------------------------
    public function __construct()
    {

        // -------------------------------------------------
        // Nextcloud integration settings
        // -------------------------------------------------
        $this->clientId = config('services.nextcloud.client_id');
        $this->clientSecret = config('services.nextcloud.client_secret');
        $this->redirectUri = config('services.nextcloud.redirect');
        $this->baseUrl = config('services.nextcloud.base_url');

        // -------------------------------------------------
        // Share menus with all views
        // -------------------------------------------------
        $menus = Menu::with(['items' => function ($query) {
            $query->whereNull('parent_id')->with('children');
        }])->get();

        // -------------------------------------------------
        // Share the menus with all views
        // -------------------------------------------------
        view()->share('menus', $menus);
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - REDIRECT TO NEXTCLOUD
    // --------------------------------------------------------------------------------------------------
    // This function redirects the user to the Nextcloud OAuth2 authorization page.
    // --------------------------------------------------------------------------------------------------
    public function redirectToNextcloud()
    {
        // -------------------------------------------------
        // Build query parameters for the Nextcloud OAuth2 authorization page.
        // -------------------------------------------------
        $queryParams = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'read write', // Juster etter behov
        ]);

        // -------------------------------------------------
        // Redirect the user to the Nextcloud OAuth2 authorization page.
        // -------------------------------------------------
        return redirect("{$this->baseUrl}/apps/oauth2/authorize?$queryParams");
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - HANDLE NEXTCLOUD CALLBACK
    // --------------------------------------------------------------------------------------------------
    // This function handles the callback from Nextcloud after the user has authorized the application.
    // --------------------------------------------------------------------------------------------------
    public function handleNextcloudCallback(Request $request)
    {

        // -------------------------------------------------
        // Try to get the access token
        // -------------------------------------------------
        try {
            
            // -------------------------------------------------
            // Create a new Guzzle HTTP client
            // -------------------------------------------------
            $client = new \GuzzleHttp\Client();

            // -------------------------------------------------
            // Get the access token from Nextcloud
            // -------------------------------------------------
            $response = $client->post(config('services.nextcloud.base_url') . '/apps/oauth2/api/v1/token', [
                'form_params' => [
                    'client_id' => config('services.nextcloud.client_id'),
                    'client_secret' => config('services.nextcloud.client_secret'),
                    'grant_type' => 'authorization_code',
                    'code' => $request->get('code'),
                    'redirect_uri' => config('services.nextcloud.redirect'),
                ],
            ]);

            // -------------------------------------------------
            // Decode the response
            // -------------------------------------------------
            $data = json_decode($response->getBody()->getContents(), true);

            // -------------------------------------------------
            // Check if the access token is returned
            // -------------------------------------------------
            if (!isset($data['access_token'])) {
                throw new \Exception('Ingen tilgangstoken ble returnert fra Nextcloud.');
            }

            // -------------------------------------------------
            // Get the access token
            // -------------------------------------------------
            $accessToken = $data['access_token'];

            // -------------------------------------------------
            // Get user data from Nextcloud
            // -------------------------------------------------
            $userResponse = $client->get(config('services.nextcloud.base_url') . '/ocs/v2.php/cloud/user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'OCS-APIRequest' => 'true',
                ],
            ]);

            // -------------------------------------------------
            // Decode the user data
            // -------------------------------------------------
            $userData = simplexml_load_string($userResponse->getBody()->getContents(), "SimpleXMLElement", LIBXML_NOCDATA);
            $userData = json_decode(json_encode($userData), true);

            // -------------------------------------------------
            // Check if the user ID is returned
            // -------------------------------------------------
            if (!isset($userData['data']['id'])) {
                throw new \Exception('Feil under henting av brukerdata fra Nextcloud.');
            }

            // -------------------------------------------------
            // Check if the user email is returned
            // -------------------------------------------------
            $existingUser = \App\Models\User::where('email', $userData['data']['email'])->first();

            // -------------------------------------------------
            // Check if the user exists
            // -------------------------------------------------
            if (!$existingUser) {
                
                // -------------------------------------------------
                // If the user does not exist, create a new user
                // -------------------------------------------------
                $existingUser = \App\Models\User::create([
                    'name' => $userData['data']['displayname'],
                    'email' => $userData['data']['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make(uniqid()), // Midlertidig passord
                    'nextcloud_token' => $accessToken,
                ]);
            }

            // -------------------------------------------------
            // Log in the user
            // -------------------------------------------------
            Auth::login($existingUser);

            // -------------------------------------------------
            // Update the user's Nextcloud token
            // -------------------------------------------------
            $existingUser->nextcloud_token = $accessToken;
            $existingUser->save();

            // -------------------------------------------------
            // Create an API token for sanctum
            // -------------------------------------------------
            $apiToken = $existingUser->createToken('API Token')->plainTextToken;

            // -------------------------------------------------
            // return redirect()->route('dashboard')->with('success', 'Du er logget inn via Nextcloud!');
            // -------------------------------------------------
            return redirect()->route('dashboard')->with('success', 'Du er logget inn via Nextcloud!');

        // -------------------------------------------------
        // Catch any exceptions
        // -------------------------------------------------
        } catch (\Exception $e) {

            // -------------------------------------------------
            // return redirect()->route('login')->with('error', 'Det oppstod en feil under Nextcloud-innloggingen: ' . $e->getMessage());
            // -------------------------------------------------
            return redirect()->route('login')->with('error', 'Det oppstod en feil under Nextcloud-innloggingen: ' . $e->getMessage());
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SHOW SETTINGS
    // --------------------------------------------------------------------------------------------------
    // This function shows the Nextcloud integration settings.
    // --------------------------------------------------------------------------------------------------
    public function showSettings()
    {

        // -------------------------------------------------
        // Get the Nextcloud integration status
        // -------------------------------------------------
        $setting = Setting::where('name', 'nextcloud_integration')->first();
        $isNextcloudActive = $setting ? $setting->value == '1' : false;

        // -------------------------------------------------
        // Return the view with the Nextcloud integration settings
        // -------------------------------------------------
        return view('admin.integrations.nextcloud.show', [
            'isNextcloudActive' => $isNextcloudActive,
            'user' => Auth::user(),
        ]);
    }


    // --------------------------------------------------------------------------------------------------
    // FUNCTION - TOGGLE NEXTCLOUD INTEGRATION
    // --------------------------------------------------------------------------------------------------
    // This function toggles the Nextcloud integration status.
    // --------------------------------------------------------------------------------------------------
    public function toggleNextcloudIntegration()
    {
        // -------------------------------------------------
        // Get the Nextcloud integration setting
        // -------------------------------------------------
        $setting = Setting::firstOrCreate(['name' => 'nextcloud_integration'], [
            'description' => 'Aktiver eller deaktiver Nextcloud-integrasjonen',
            'type' => 'boolean',
            'value' => '0', // Standardverdi
        ]);

        // -------------------------------------------------
        // Toggle the Nextcloud integration status
        // -------------------------------------------------
        $setting->value = $setting->value == '1' ? '0' : '1';
        $setting->updated_by = Auth::id();
        $setting->save();

        // -------------------------------------------------
        // Redirect the user back to the Nextcloud integration settings page with a success message
        // -------------------------------------------------
        return redirect()->back()->with('success', 'Nextcloud-integrasjonen er ' . ($setting->value == '1' ? 'aktivert' : 'deaktivert'));
    }
}
