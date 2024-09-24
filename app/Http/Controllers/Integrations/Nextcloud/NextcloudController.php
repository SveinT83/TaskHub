<?php

namespace App\Http\Controllers\Integrations\Nextcloud;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class NextcloudController extends Controller
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.nextcloud.client_id');
        $this->clientSecret = config('services.nextcloud.client_secret');
        $this->redirectUri = config('services.nextcloud.redirect');
        $this->baseUrl = config('services.nextcloud.base_url');
    }

    /**
     * Redirect the user to Nextcloud OAuth2 authorization page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToNextcloud()
    {
        $queryParams = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'read write', // Juster etter behov
        ]);

        return redirect("{$this->baseUrl}/apps/oauth2/authorize?$queryParams");
    }

    /**
     * Handle the callback from Nextcloud after user authorization.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleNextcloudCallback(Request $request)
    {
        try {
            // Bytt kode for tilgangstoken
            $client = new \GuzzleHttp\Client();
            $response = $client->post(config('services.nextcloud.base_url') . '/apps/oauth2/api/v1/token', [
                'form_params' => [
                    'client_id' => config('services.nextcloud.client_id'),
                    'client_secret' => config('services.nextcloud.client_secret'),
                    'grant_type' => 'authorization_code',
                    'code' => $request->get('code'),
                    'redirect_uri' => config('services.nextcloud.redirect'),
                ],
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
    
            \Log::info('Nextcloud token response:', $data ?? []);
    
            if (!isset($data['access_token'])) {
                throw new \Exception('Ingen tilgangstoken ble returnert fra Nextcloud.');
            }
    
            $accessToken = $data['access_token'];
    
            // Hent brukerdata fra Nextcloud med tilgangstoken
            $userResponse = $client->get(config('services.nextcloud.base_url') . '/ocs/v2.php/cloud/user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'OCS-APIRequest' => 'true',
                ],
            ]);
    
            // Konverter XML-responsen til et PHP-array
            $userData = simplexml_load_string($userResponse->getBody()->getContents(), "SimpleXMLElement", LIBXML_NOCDATA);
            $userData = json_decode(json_encode($userData), true); // Konverter til array
            
            \Log::info('Nextcloud user response (XML):', $userData ?? []);
    
            // Sjekk om brukerdataene er gyldige
            if (!isset($userData['data']['id'])) {
                throw new \Exception('Feil under henting av brukerdata fra Nextcloud.');
            }
    
            // Lagre Nextcloud-token og evt. brukerdata
            $user = Auth::user();
            $user->nextcloud_token = $accessToken;
            $user->save();
    
            return redirect()->route('nextcloud.settings')->with('success', 'Nextcloud-tilkoblingen ble vellykket!');
        } catch (\Exception $e) {
            \Log::error('Nextcloud authentication error: ' . $e->getMessage());
            return redirect()->route('nextcloud.settings')->with('error', 'Det oppstod en feil under Nextcloud-tilkoblingen: ' . $e->getMessage());
        }
    }
    

    public function showSettings()
    {
        // Hent Nextcloud-integrasjonsinnstillingen
        $setting = Setting::where('name', 'nextcloud_integration')->first();
        $isNextcloudActive = $setting ? $setting->value == '1' : false;

        return view('admin.integrations.nextcloud.show', [
            'isNextcloudActive' => $isNextcloudActive,
            'user' => Auth::user(),
        ]);
    }

    public function toggleNextcloudIntegration()
    {
        // Hent innstilling fra databasen eller opprett ny
        $setting = Setting::firstOrCreate(['name' => 'nextcloud_integration'], [
            'description' => 'Aktiver eller deaktiver Nextcloud-integrasjonen',
            'type' => 'boolean',
            'value' => '0', // Standardverdi
        ]);

        // Bytt status for Nextcloud-integrasjon
        $setting->value = $setting->value == '1' ? '0' : '1';
        $setting->updated_by = Auth::id();
        $setting->save();

        return redirect()->back()->with('success', 'Nextcloud-integrasjonen er ' . ($setting->value == '1' ? 'aktivert' : 'deaktivert'));
    }
}
