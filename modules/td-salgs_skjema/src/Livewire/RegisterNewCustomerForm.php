<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\TripletexService;

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
    // Delivery address
    // -------------------------------------------------
    public $sameAs = false; // If the delivery address is the same as the billing address
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
    // Org nr and Tripletex
    // -------------------------------------------------
    public $orgNr;
    public $customerData = [];

    // -------------------------------------------------
    // Validation rules
    // -------------------------------------------------
    protected $rules = [
        'adrAdresse' => 'required|string|max:255',
        'adrPostnr' => 'required|numeric|digits:4',
        'adrSted' => 'required|string|max:255',
        'orgNr' => 'nullable|digits:9', // Validerer at det er 11 sifre
    ];



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

                /*
                    if ($customer) {
                        session()->flash('success', "Kunde funnet: {$customer[0]['name']}");
                        $this->customerData = $customer[0]; // Fyll ut skjema
                    } else {
                        session()->flash('error', 'Ingen kunde funnet med dette organisasjonsnummeret.');
                    }
                */

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

        // -------------------------------------------------
        // Check Same As?
        // -------------------------------------------------
        $this->checkSameAs();
        
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
    // FUNCTION - CHECK SAME AS
    // --------------------------------------------------------------------------------------------------
    // This function checks if adress form is filled in and sets the var true or false
    // --------------------------------------------------------------------------------------------------
    public function checkSameAs()
    {

        // -------------------------------------------------
        // Set sameAs to true if all fields are filled out
        // -------------------------------------------------

        if (!empty($this->adrAdresse) && !empty($this->adrPostnr) && !empty($this->adrSted)) {
            $this->sameAs = true;
        } else {
            $this->sameAs = false;
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATED
    // --------------------------------------------------------------------------------------------------
    // This function is called when a field is updated
    // --------------------------------------------------------------------------------------------------
    public function updated($propertyName)
    {

        // -------------------------------------------------
        // Validate the field
        // -------------------------------------------------
        $this->validateOnly($propertyName);

        // -------------------------------------------------
        // If the fields are filled out, set sameAs to true
        // -------------------------------------------------
        if (in_array($propertyName, ['adrAdresse', 'adrPostnr', 'adrSted'])) {
            
            $this->checkSameAs();
        }
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