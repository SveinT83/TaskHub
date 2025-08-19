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
use Illuminate\Support\Facades\DB;

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
        try {
            // Check if Nextcloud integration is active
            if (!$this->integration || !$this->integration->active) {
                return redirect()->route('login')
                    ->with('error', 'Nextcloud integration is not enabled.');
            }

            // Get the Nextcloud credentials from the database using the relationship
            $credentials = $this->integration->credentials->pluck('value', 'key')->toArray();
            
            // Validate required credentials
            if (empty($credentials) || !isset($credentials['clientid'], $credentials['baseurl'], $credentials['redirecturi'])) {
                \Log::error('Nextcloud credentials missing', ['available_keys' => array_keys($credentials)]);
                return redirect()->route('login')
                    ->with('error', 'Nextcloud integration is not properly configured.');
            }

            // Build query parameters for the Nextcloud OAuth2 authorization page
            $queryParams = [
                'client_id' => $credentials['clientid'],
                'redirect_uri' => $credentials['redirecturi'],
                'response_type' => 'code',
                'scope' => 'read write',
                'state' => \Illuminate\Support\Str::random(40), // Add state for security
            ];

            // Store state in session for validation in callback
            session(['nextcloud_oauth_state' => $queryParams['state']]);

            // Manually build the query string to avoid double encoding
            $queryString = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);

            \Log::info('Redirecting to Nextcloud OAuth', [
                'base_url' => $credentials['baseurl'],
                'client_id' => $credentials['clientid'],
                'redirect_uri' => $credentials['redirecturi']
            ]);

            // Redirect the user to the Nextcloud OAuth2 authorization page
            return redirect("{$credentials['baseurl']}/apps/oauth2/authorize?$queryString");
            
        } catch (\Exception $e) {
            \Log::error('Nextcloud redirect error', ['message' => $e->getMessage()]);
            return redirect()->route('login')
                ->with('error', 'Failed to connect to Nextcloud. Please try again.');
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - HANDLE NEXTCLOUD CALLBACK
    // --------------------------------------------------------------------------------------------------
    // This function handles the callback from Nextcloud after the user has authorized the application.
    // --------------------------------------------------------------------------------------------------
    public function handleNextcloudCallback(Request $request)
    {
        try {
            \Log::info('Nextcloud callback started', ['request_data' => $request->all()]);
            
            // Validate state parameter for security
            $expectedState = session('nextcloud_oauth_state');
            $receivedState = $request->get('state');
            
            if (!$expectedState || $expectedState !== $receivedState) {
                \Log::warning('Nextcloud OAuth state mismatch', [
                    'expected' => $expectedState,
                    'received' => $receivedState
                ]);
                return redirect()->route('login')
                    ->with('error', 'Invalid OAuth state. Please try again.');
            }
            
            // Clear the state from session
            session()->forget('nextcloud_oauth_state');
            
            // Check for authorization errors
            if ($request->has('error')) {
                \Log::warning('Nextcloud OAuth error', ['error' => $request->get('error')]);
                return redirect()->route('login')
                    ->with('error', 'Nextcloud authorization was denied: ' . $request->get('error'));
            }
            
            // Check if authorization code is present
            if (!$request->has('code')) {
                \Log::error('Nextcloud callback missing authorization code');
                return redirect()->route('login')
                    ->with('error', 'Authorization code not received from Nextcloud.');
            }
            
            // Check if Nextcloud integration is still active
            if (!$this->integration || !$this->integration->active) {
                return redirect()->route('login')
                    ->with('error', 'Nextcloud integration is not enabled.');
            }
            
            // Get Nextcloud credentials from database using the relationship
            $credentials = $this->integration->credentials->pluck('value', 'key')->toArray();
            \Log::info('Credentials loaded', ['has_credentials' => !empty($credentials)]);
            
            // Validate required credentials
            if (empty($credentials) || !isset($credentials['clientid'], $credentials['clientsecret'], $credentials['baseurl'], $credentials['redirecturi'])) {
                \Log::error('Nextcloud credentials incomplete', ['available_keys' => array_keys($credentials)]);
                return redirect()->route('login')
                    ->with('error', 'Nextcloud integration is not properly configured.');
            }

            // Decrypt client secret
            $clientSecret = Crypt::decryptString($credentials['clientsecret']);

            // Create a new Guzzle HTTP client
            $client = new Client();

            // Get access token from Nextcloud
            $response = $client->post("{$credentials['baseurl']}/apps/oauth2/api/v1/token", [
                'form_params' => [
                    'client_id' => $credentials['clientid'],
                    'client_secret' => $clientSecret,
                    'grant_type' => 'authorization_code',
                    'code' => $request->get('code'),
                    'redirect_uri' => $credentials['redirecturi'],
                ],
            ]);

            // Decode the response
            $data = json_decode($response->getBody()->getContents(), true);
            \Log::info('Token response', ['has_access_token' => isset($data['access_token'])]);

            // Check if access token is returned
            if (!isset($data['access_token'])) {
                throw new \Exception('No access token was returned from Nextcloud.');
            }

            // Get access token
            $accessToken = $data['access_token'];

            // Get user data from Nextcloud
            $userResponse = $client->get("{$credentials['baseurl']}/ocs/v2.php/cloud/user", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'OCS-APIRequest' => 'true',
                ],
            ]);

            // Decode user data
            $userData = simplexml_load_string($userResponse->getBody()->getContents(), "SimpleXMLElement", LIBXML_NOCDATA);
            $userData = json_decode(json_encode($userData), true);
            \Log::info('User data retrieved', ['user_email' => $userData['data']['email'] ?? 'not found']);

            // Check if user data is valid
            if (!isset($userData['data']['id']) || !isset($userData['data']['email'])) {
                throw new \Exception('Error retrieving user data from Nextcloud.');
            }

            // Check if user already exists
            $existingUser = \App\Models\User::where('email', $userData['data']['email'])->first();
            \Log::info('User lookup', ['user_exists' => !is_null($existingUser), 'email' => $userData['data']['email']]);

            // If user doesn't exist, create a new user
            if (!$existingUser) {
                $existingUser = \App\Models\User::create([
                    'name' => $userData['data']['displayname'] ?? $userData['data']['id'],
                    'email' => $userData['data']['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)), // Random password
                    'nextcloud_token' => $accessToken,
                    'email_verified_at' => now(), // Mark email as verified for Nextcloud users
                ]);
                \Log::info('New user created', ['user_id' => $existingUser->id]);
            } else {
                // Update existing user's token
                $existingUser->nextcloud_token = $accessToken;
                
                // If user exists but email is not verified, verify it
                if (!$existingUser->email_verified_at) {
                    $existingUser->email_verified_at = now();
                }
                
                $existingUser->save();
                \Log::info('Existing user updated', ['user_id' => $existingUser->id, 'email_verified' => !is_null($existingUser->email_verified_at)]);
            }

            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();
            
            // Log in the user
            Auth::login($existingUser, true); // Remember the user
            \Log::info('User logged in', ['auth_check' => Auth::check(), 'auth_user_id' => Auth::id()]);

            // Verify authentication worked
            if (!Auth::check()) {
                throw new \Exception('Failed to authenticate user after login.');
            }

            \Log::info('Redirecting to dashboard', ['auth_check' => Auth::check()]);
            
            // Redirect to intended page or dashboard
            $intendedUrl = session()->pull('url.intended', route('dashboard'));
            return redirect($intendedUrl)->with('success', 'Successfully logged in with Nextcloud!');
            
        } catch (\Exception $e) {
            \Log::error('Nextcloud login error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->with('error', 'An error occurred during Nextcloud login: ' . $e->getMessage());
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
        // Generate default redirect URI based on current installation
        // For user login, we use the login callback route
        // -------------------------------------------------
        $defaultRedirectUri = url('/login/nextcloud/callback');

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
            'defaultRedirectUri' => $defaultRedirectUri,
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
        // Toggle the Nextcloud integration active status
        // -------------------------------------------------
        $this->integration->active = !$this->integration->active;
        $this->integration->save();

        // -------------------------------------------------
        // Update menu items based on status
        // -------------------------------------------------
        if ($this->integration->active) {
            // Add menu item if not exists
            $menuExists = DB::table('menu_items')
                ->where('url', '/admin/integration/' . strtolower($this->integration->name))
                ->exists();
                
            if (!$menuExists) {
                DB::table('menu_items')->insert([
                    'title' => ucfirst($this->integration->name),
                    'url' => '/admin/integration/' . strtolower($this->integration->name),
                    'menu_id' => 1,
                    'parent_id' => 4,
                    'icon' => $this->integration->icon ?? 'bi bi-cloud',
                    'is_parent' => 0,
                    'order' => DB::table('menu_items')->max('order') + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Remove menu item
            DB::table('menu_items')
                ->where('url', '/admin/integration/' . strtolower($this->integration->name))
                ->delete();
        }

        // -------------------------------------------------
        // Redirect the user back to the Nextcloud integration settings page with a success message
        // -------------------------------------------------
        $status = $this->integration->active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', 'Nextcloud integration has been ' . $status . '.');
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE CREDENTIALS
    // --------------------------------------------------------------------------------------------------
    // This function updates or creates new credentials in the integration_credentials table.
    // --------------------------------------------------------------------------------------------------
    public function updateCredentials(Request $request)
    {
        $request->validate([
            'baseurl' => 'required|url',
            'clientid' => 'required|string',
            'clientsecret' => 'nullable|string'
        ]);

        // Get existing credentials
        $existingCredentials = IntegrationCredential::where('integration_id', $this->integration->id)->first();
        
        // Generate redirect URI automatically
        $redirectUri = url('/auth/nextcloud/callback');
        
        $credentials = [
            'baseurl' => $request->input('baseurl'),
            'clientid' => $request->input('clientid'),
            'redirecturi' => $redirectUri, // Always use the generated URI
            'updated_at' => now()
        ];

        // Only update client secret if a new one is provided (not the masked version)
        $clientSecret = $request->input('clientsecret');
        if ($clientSecret && $clientSecret !== '••••••••••••') {
            $credentials['clientsecret'] = Crypt::encryptString($clientSecret);
        } elseif (!$existingCredentials) {
            // If no existing credentials and no secret provided, require it
            return redirect()->back()->withErrors(['clientsecret' => 'Client Secret is required.']);
        }

        IntegrationCredential::updateOrCreate(
            ['integration_id' => $this->integration->id],
            $credentials
        );

        return redirect()->back()->with('success', 'Credentials updated successfully. Redirect URI has been automatically set to: ' . $redirectUri);
    }
}
