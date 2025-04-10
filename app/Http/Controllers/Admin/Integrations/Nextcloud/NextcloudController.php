<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - NEXTCLOUD CONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling Nextcloud integration related actions such as redirecting the user to Nextcloud OAuth2 authorization page,
// handling the callback from Nextcloud after user authorization, showing the Nextcloud integration settings, and toggling the Nextcloud integration status.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Integrations\Nextcloud;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\Integrations\IntegrationCredential;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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
    protected $integration;



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
        $this->integration = Integration::where('name', 'Nextcloud')->first();

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
        // Get the Nextcloud credentials from the database
        // -------------------------------------------------
        $credentials = IntegrationCredential::where('integration_id', $this->integration->id)->first();

        // -------------------------------------------------
        // Decrypt the client secret
        // -------------------------------------------------
        $clientSecret = Crypt::decryptString($credentials->clientsecret);

        // -------------------------------------------------
        // Build query parameters for the Nextcloud OAuth2 authorization page.
        // -------------------------------------------------
        $queryParams = [
            'client_id' => $credentials->clientid,
            'redirect_uri' => $credentials->redirecturi, // Ensure the redirect URI is not double encoded
            'response_type' => 'code',
            'scope' => 'read write', // Adjust as needed
        ];

        // -------------------------------------------------
        // Manually build the query string to avoid double encoding
        // -------------------------------------------------
        $queryString = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);

        // -------------------------------------------------
        // Redirect the user to the Nextcloud OAuth2 authorization page.
        // -------------------------------------------------
        return redirect("{$credentials->baseurl}/apps/oauth2/authorize?$queryString");
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - HANDLE NEXTCLOUD CALLBACK
    // --------------------------------------------------------------------------------------------------
    // This function handles the callback from Nextcloud after the user has authorized the application.
    // --------------------------------------------------------------------------------------------------
    public function handleNextcloudCallback(Request $request)
    {
        try {
            // Hent Nextcloud credentials fra databasen
            $credentials = IntegrationCredential::where('integration_id', $this->integration->id)->get()->pluck('value', 'key')->toArray();

            // Dekrypter client secret
            $clientSecret = Crypt::decryptString($credentials['clientsecret']);

            // Opprett en ny Guzzle HTTP-klient
            $client = new Client();

            // Hent access token fra Nextcloud
            $response = $client->post("{$credentials['baseurl']}/apps/oauth2/api/v1/token", [
                'form_params' => [
                    'client_id' => $credentials['clientid'],
                    'client_secret' => $clientSecret,
                    'grant_type' => 'authorization_code',
                    'code' => $request->get('code'),
                    'redirect_uri' => $credentials['redirecturi'],
                ],
            ]);

            // Dekod responsen
            $data = json_decode($response->getBody()->getContents(), true);

            // Sjekk om access token er returnert
            if (!isset($data['access_token'])) {
                throw new \Exception('Ingen tilgangstoken ble returnert fra Nextcloud.');
            }

            // Hent access token
            $accessToken = $data['access_token'];

            // Hent brukerdata fra Nextcloud
            $userResponse = $client->get("{$credentials['baseurl']}/ocs/v2.php/cloud/user", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'OCS-APIRequest' => 'true',
                ],
            ]);

            // Dekod brukerdata
            $userData = simplexml_load_string($userResponse->getBody()->getContents(), "SimpleXMLElement", LIBXML_NOCDATA);
            $userData = json_decode(json_encode($userData), true);

            // Sjekk om bruker-ID er returnert
            if (!isset($userData['data']['id'])) {
                throw new \Exception('Feil under henting av brukerdata fra Nextcloud.');
            }

            // Sjekk om brukeren allerede eksisterer
            $existingUser = \App\Models\User::where('email', $userData['data']['email'])->first();

            // Hvis brukeren ikke eksisterer, opprett en ny bruker
            if (!$existingUser) {
                $existingUser = \App\Models\User::create([
                    'name' => $userData['data']['displayname'],
                    'email' => $userData['data']['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make(uniqid()), // Midlertidig passord
                    'nextcloud_token' => $accessToken,
                ]);
            }

            // Logg inn brukeren
            Auth::login($existingUser);

            // Oppdater brukerens Nextcloud token
            $existingUser->nextcloud_token = $accessToken;
            $existingUser->save();

            // Opprett en API token for sanctum
            $apiToken = $existingUser->createToken('API Token')->plainTextToken;

            return redirect()->route('dashboard')->with('success', 'Du er logget inn via Nextcloud!');
        } catch (\Exception $e) {
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
        $credentials = IntegrationCredential::where('integration_id', $this->integration->id)->first();
        $isNextcloudActive = $this->integration->active;

        // -------------------------------------------------
        // Decrypt the client secret
        // -------------------------------------------------
        if ($credentials && isset($credentials->clientsecret)) {
            $credentials->clientsecret = Crypt::decryptString($credentials->clientsecret);
        }

        // -------------------------------------------------
        // Return the view with the Nextcloud integration settings
        // -------------------------------------------------
        return view('admin.integrations.nextcloud.show', [
            'isNextcloudActive' => $isNextcloudActive,
            'credentials' => $credentials,
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
            'description' => 'Activate or deactivate the Nextcloud integration',
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

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE CREDENTIALS
    // --------------------------------------------------------------------------------------------------
    // This function updates or creates new credentials in the integration_credentials table.
    // --------------------------------------------------------------------------------------------------
    public function updateCredentials(Request $request)
    {
        $credentials = [
            'baseurl' => $request->input('baseurl'),
            'clientid' => $request->input('clientid'),
            'clientsecret' => Crypt::encryptString($request->input('clientsecret')),
            'redirecturi' => $request->input('redirecturi')
        ];

        IntegrationCredential::updateOrCreate(
            ['integration_id' => $this->integration->id],
            $credentials
        );

        return redirect()->back()->with('success', 'Credentials updated successfully.');
    }
}
