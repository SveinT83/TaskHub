<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\TripletexService;
use Illuminate\Support\Facades\Mail;

class RegisterNewCustomerForm extends Component
{
    // --------------------------------------------------------------------------------------------------
    // VARIABLE'S
    // --------------------------------------------------------------------------------------------------
    // Variables that can be used in the view
    // --------------------------------------------------------------------------------------------------

    // -------------------------------------------------
    // Business or private? variables
    // -------------------------------------------------
    public $business = false;
    public $private = false;

    // -------------------------------------------------
    // Org nr and Tripletex
    // -------------------------------------------------
    public $orgNr;
    public $orgName = '';
    public $customerData = [];

    // -------------------------------------------------
    // Contact information
    // -------------------------------------------------
    public $kontaktNavn = '';
    public $kontaktEpost = '';
    public $kontaktTlf = '';

    // -------------------------------------------------
    // Delivery address
    // -------------------------------------------------
    public $adrAdresse = '';
    public $adrPostnr = '';
    public $adrSted = '';

    // -------------------------------------------------
    // Billing address
    // -------------------------------------------------
    public $showFaktAdresse = true;

    public $faktAdresse = '';
    public $faktPostnr = '';
    public $faktSted = '';

    // -------------------------------------------------
    // Validation rules
    // -------------------------------------------------
    protected $rules = [
        'adrAdresse' => 'required|string|max:255',
        'adrPostnr' => 'required|numeric|digits:4',
        'adrSted' => 'required|string|max:255',
        'orgNr' => 'nullable|digits:11',
        'orgName' => 'nullable|string|max:50',
        'kontaktNavn' => 'string|max:50',
        'kontaktEpost' => 'string|max:50',
        'kontaktTlf' => 'string|digits:8',
    ];



    // --------------------------------------------------------------------------------------------------
    // FUNCTIONS - VALIDATE FORM
    // --------------------------------------------------------------------------------------------------
    // This function validates the form data and sends it via mail
    // --------------------------------------------------------------------------------------------------
    public function validateForm()
    {
        // Valider alle feltene
        $validatedData = $this->validate([
            'kontaktNavn' => 'required|string|max:50',
            'kontaktEpost' => 'required|email|max:50',
            'kontaktTlf' => 'required|digits:8',

            'adrAdresse' => 'required|string|max:255',
            'adrPostnr' => 'required|numeric|digits:4',
            'adrSted' => 'required|string|max:255',

            'faktAdresse' => 'string|max:255',
            'faktPostnr' => 'numeric|digits:4',
            'faktSted' => 'string|max:255',

            'orgNr' => 'nullable|digits:9',
            'orgName' => 'nullable|string|max:50',
        ]);

        // Hvis vi kommer hit, er skjemaet gyldig
        session()->flash('success', 'Skjemaet er gyldig og klart for innsending!');

        // Kall en funksjon for å behandle data
        $this->sendFormData($validatedData);
        
    }


    // --------------------------------------------------------------------------------------------------
    // FUNCTIONS - SEND FORM DATA
    // --------------------------------------------------------------------------------------------------
    // This function sends the form data via mail
    // --------------------------------------------------------------------------------------------------
    public function sendFormData($data = null)
    {
        try {

            // --------------------------------------------------------------------------------------------------
            // Create an data array with the form data and session data, and add test data if no data is sent
            // --------------------------------------------------------------------------------------------------

            // -------------------------------------------------
            // Use test data if no data is sent
            // -------------------------------------------------
            $data = $data ?? [
                'kontaktNavn' => 'Test Person',
                'kontaktEpost' => 'test@example.com',
                'kontaktTlf' => '12345678',
                'adrAdresse' => 'Testgate 1',
                'adrPostnr' => '1234',
                'adrSted' => 'Testbyen',
                'faktAdresse' => 'Testgate 1',
                'faktPostnr' => '1234',
                'faktSted' => 'Testbyen',
                'orgNr' => '123456789',
                'orgName' => 'Testfirma AS',
            ];

            // --------------------------------------------------------------------------------------------------
            // Add session data to the data array if available
            // --------------------------------------------------------------------------------------------------

            // -------------------------------------------------
            // Session keys
            // -------------------------------------------------
            $sessionKeys = [
                'private',             // Session key for private customer
                'business',            // Session key for business customer
                'timebank',            // Session key for timebank
                'amountUsers',         // Session key for number of users
                'amountDatamaskiner',  // Session key for number of computers
                'serviceName',         // Session key for service name

                'sumTottComputers',    // Session key for total number of computers
                'timebankPrice',       // Session key for timebank price
                'sumTott',             // Session key for total price
                'cpu',                 // Session key for cpu
                'ram',                 // Session key for ram
                'storage',             // Session key for storage
            ];

            // -------------------------------------------------
            // Add session data to the dsessionKey array
            // -------------------------------------------------
            foreach ($sessionKeys as $key) {
                if (session()->has($key)) {
                    $data[$key] = session($key);
                }
            }

            // -------------------------------------------------
            // Set default values for missing keys
            // -------------------------------------------------
            $data['timebank'] = $data['timebank'] ?? '0';
            $data['amountUsers'] = $data['amountUsers'] ?? '1';
            $data['amountDatamaskiner'] = $data['amountDatamaskiner'] ?? '1';
            $data['selectedService'] = $data['selectedService'] ?? 'Standard Service';
            $data['serviceName'] = $data['serviceName'] ?? 'N/A';
            $data['sumTottComputers'] = $data['sumTottComputers'] ?? 'N/A';
            $data['timebankPrice'] = $data['timebankPrice'] ?? 'N/A';
            $data['sumTott'] = $data['sumTott'] ?? 'N/A';
            $data['cpu'] = $data['cpu'] ?? 'N/A';
            $data['ram'] = $data['ram'] ?? 'N/A';
            $data['storage'] = $data['storage'] ?? 'N/A';

            // --------------------------------------------------------------------------------------------------
            // Send the email
            // --------------------------------------------------------------------------------------------------

            // -------------------------------------------------
            // Send the email
            // -------------------------------------------------
            \Mail::to('post@tronderdata.no')->send(new \App\Mail\FormSubmissionMail($data));

            // -------------------------------------------------
            // Flash message for success
            // -------------------------------------------------
            session()->flash('success', 'Test-e-post sendt!');

        // --------------------------------------------------------------------------------------------------
        // Catch any exceptions
        // --------------------------------------------------------------------------------------------------

        } catch (\Exception $e) {

            // -------------------------------------------------
            // Flash message for error
            // -------------------------------------------------
            session()->flash('error', 'Noe gikk galt: ' . $e->getMessage());
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CHECK ORG NR
    // --------------------------------------------------------------------------------------------------
    // This function checks if the orgNr is filled out and if it is 11 digits long
    // --------------------------------------------------------------------------------------------------
    public function checkOrgNr()
    {
        if (!empty($this->orgNr) && strlen((string) $this->orgNr) === 9) {
            try {
                $tripletexService = new TripletexService();
                $customer = $tripletexService->searchCustomerByOrgNr($this->orgNr);

            } catch (\Exception $e) {
                session()->flash('error', 'Feil under oppslag: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'Organisasjonsnummeret må være 9 sifre.');
        }
    }




    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SEARCH CUSTOMER IN TRIPLETEX
    // --------------------------------------------------------------------------------------------------
    // This function simulates a search in Tripletex
    // --------------------------------------------------------------------------------------------------
    private function searchCustomerInTripletex($orgNr)
    {
        // Simuler en API-forespørsel
        // Dette kan utvides med faktisk API-kall til Tripletex
        session()->flash('success', "Sjekker organisasjonsnummer: {$orgNr}");

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.tripletex.api_key'),
        ])->get('https://tripletex.no/v2/some-endpoint');
        
        // Håndter responsen
        if ($response->successful()) {
            $data = $response->json();
            // Gjør noe med dataene
        } else {
            // Håndter feil
            throw new \Exception('Tripletex API Error: ' . $response->body());
        }
    }


    
    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // Kjør denne funksjonen når komponenten mountes
    // --------------------------------------------------------------------------------------------------
    public function mount() {

        // -------------------------------------------------
        // Business or private?
        // -------------------------------------------------
        $this->businessOrPrivate();
        
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATED SAME AS
    // --------------------------------------------------------------------------------------------------
    // This function fills the billing adress with the delivery adress if the sameAs is set to true and activated
    // --------------------------------------------------------------------------------------------------
    public function toggleSameAs()
    {

        // -------------------------------------------------
        // If sameAs is true, fill in the billing address with the delivery address
        // -------------------------------------------------
        $this->faktAdresse = $this->adrAdresse;
        $this->faktPostnr = $this->adrPostnr;
        $this->faktSted = $this->adrSted;
        
        // -------------------------------------------------
        // If sameAs is true hide the billing address form
        // -------------------------------------------------
        $this->showFaktAdresse = false;

    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - BUSINESS OR PRIVATE
    // --------------------------------------------------------------------------------------------------
    // This function checks if business or private is set in the session and sets the var
    // --------------------------------------------------------------------------------------------------
    public function businessOrPrivate() {

        // -------------------------------------------------
        // Sett session var
        // -------------------------------------------------

        // Sjekk om business er satt i sesjonen
        if (session()->has('business')) {
            $this->business = session('business');
        }

        // Sjekk om private er satt i sesjonen
        else if (session()->has('private')) {
            $this->private = session('private');
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the view
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.RegisterNewCustomerForm');
    }
}