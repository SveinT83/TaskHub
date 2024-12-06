<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;
use tronderdata\TdSalgsSkjema\Models\ServicePakke;

class serviceavtaleConfigForm extends Component
{

    // -------------------------------------------------
    // VARIABLES
    // -------------------------------------------------
    public $private = false;                   // Indikerer om tjenesten er for privatperson
    public $business = false;                  // Indikerer om tjenesten er for bedrift
    public $amount = 0;                        // Total mengde brukere/datamaskiner
    public $sumTott = 0;                       // Total sum for tjenesten

    // Beregninger for datamaskiner
    public $sumTottComputers = 0;              // Totalsum for datamaskiner
    public $amountDatamaskiner = 0;            // Antall datamaskiner
    public $normalComputers = 0;               // Normalpris for datamaskiner
    public $normalComputersPrice = 0;          // Pris for normale datamaskiner
    public $extraComputers = 0;                // Ekstra datamaskiner utover inkluderte
    public $extraComputersPrice = 0;           // Pris for ekstra datamaskiner

    // Beregninger for brukere
    public $sumTottUsers = 0;                  // Totalsum for brukere
    public $amountUsers = 0;                   // Antall brukere
    public $normalUsers = 0;                   // Antall normale brukere
    public $basePrice_normal_user = 0;         // Basispris per normal bruker
    public $normalUsersPrice = 0;              // Pris for normale brukere
    public $basePrice_extra_user = 0;          // Basispris per ekstra bruker
    public $basicUsers = 0;                    // Antall ekstra brukere utover inkluderte
    public $basicUsersPrice = 0;               // Pris for ekstra brukere

    // Diverse
    public $acResult = 0;                      // Resultat fra beregning av brukere vs datamaskiner
    public $prices = 0;                        // Totalpriser
    public $servicePakke = false;              // Den valgte servicepakken
    public $serviceData = false;               // Data om servicepakken

    // Timebank
    public $timebank = 0;                      // Antall timer i timebanken 
    public $timebankPrice = 0;                 // Pris for timebanken

    // NextCloud VM
    public $cpu = false;
    public $ram = 2;
    public $storage = 20;



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // Kjør denne funksjonen når komponenten mountes
    // --------------------------------------------------------------------------------------------------
    public function mount() {

        // -------------------------------------------------
        // Sett private eller business basert på session
        // -------------------------------------------------
        if (session('private')) {
            $this->private = session('private');
        } elseif (session('business')) {
            $this->business = session('business');
        }

        // -------------------------------------------------
        // Hent servicepakke basert på session
        // -------------------------------------------------
        if (session('selectedService')) {
            $servicePakke = ServicePakke::find(session('selectedService')); // Hent basert på ID fra session

            if ($servicePakke) {
                $this->serviceData = $servicePakke;
            } else {
                session()->flash('error', 'Servicepakke ikke funnet.');
                return redirect()->route('tdsalgsskjema.serviceavtale');
            }
        } else {
            return redirect()->route('tdsalgsskjema.serviceavtale');
        }

        // -------------------------------------------------
        // Kontroller at antall brukere og datamaskiner er satt
        // -------------------------------------------------
        if (!session('amountUsers')) {
            return redirect()->route('tdsalgsskjema.amountUsers');
        }
        $this->amountUsers = session('amountUsers');

        if (!session('amountDatamaskiner')) {
            //return redirect()->route('tdsalgsskjema.amountDatamaskiner');
        }
        $this->amountDatamaskiner = session('amountDatamaskiner');

        // -------------------------------------------------
        // Beregn antall normale og ekstra datamaskiner og brukere
        // -------------------------------------------------
        $this->acResult = $this->usersVsComputers();

        // -------------------------------------------------
        // Timebank - Hent eller beregn
        // -------------------------------------------------
        $this->timebank();

        // -------------------------------------------------
        // Beregn pris for brukere og datamaskiner
        // -------------------------------------------------
        $this->prices = $this->userAndComputersPrice();

        // -------------------------------------------------
        // Beregn NextCloud VM cpu, ram og lagringsplass
        // -------------------------------------------------
        $this->vmResources();
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - VMRESOURCES
    // --------------------------------------------------------------------------------------------------
    // Calculate the resources needed for the NextCloud VM
    // --------------------------------------------------------------------------------------------------
    public function vmResources() {

        // -------------------------------------------------
        // If NextCloud is selected
        // -------------------------------------------------
        if (session('selectedService') == 1) {

            // -------------------------------------------------
            // Update CPU based on amount of users
            // -------------------------------------------------
            $this->cpu += 2; //Base CPU
            $this->cpu += 0.2 * $this->amountUsers;

            // -------------------------------------------------
            // Update RAM based on amount of users
            // -------------------------------------------------
            $this->ram += 0.5 * $this->amountUsers;

            // -------------------------------------------------
            // Update Diskspace based on amount of users
            // -------------------------------------------------
            $this->storage += 100 * $this->normalUsers; //Normal users
            $this->storage += 10 * $this->basicUsers; //Basic users

        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - GETORCALCTIMEBANK
    // --------------------------------------------------------------------------------------------------
    // Get timebank from session or calculate if missing
    // --------------------------------------------------------------------------------------------------
    public function timebank() {

        // -------------------------------------------------
        // Check if timebank is set in session
        // -------------------------------------------------
        $this->timebank = session('timebank', 5);
        
        // -------------------------------------------------
        // Timebank Price
        // -------------------------------------------------
        if ($this->timebankPrice = 12 AND session('private')) {
            $this->timebankPrice = 0;
            session(['timebank' => '12']);
        }
        elseif ($this->timebankPrice = 5){
            $this->timebankPrice = 399;
        } elseif ($this->timebank == 10) {
            $this->timebankPrice = 680;
        } elseif ($this->timebank == 20) {
            $this->timebankPrice = 1160;
        } elseif ($this->timebank == 30) {
            $this->timebankPrice = 1900;
        } elseif ($this->timebank == 40) {
            $this->timebankPrice = 2532;
        } else {
            $this->timebankPrice = 0;
        }

    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - USERSVSCOMPUTERS
    // --------------------------------------------------------------------------------------------------
    // Beregn antall normale og ekstra brukere/datamaskiner
    // --------------------------------------------------------------------------------------------------
    public function usersVsComputers() {

        $users = session('amountUsers');
        $computers = session('amountDatamaskiner');

        if ($computers > $users) {
            $this->normalComputers = $users;
            $this->extraComputers = $computers - $users;
            $this->normalUsers = $users;
            $this->basicUsers = 0;
        } elseif ($users > $computers) {
            $this->normalComputers = $computers;
            $this->extraComputers = 0;
            $this->normalUsers = $computers;
            $this->basicUsers = $users - $computers;
        } else {
            $this->normalComputers = $computers;
            $this->extraComputers = 0;
            $this->normalUsers = $users;
            $this->basicUsers = 0;
        }
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - USERANDCOMPUTERSPRICE
    // --------------------------------------------------------------------------------------------------
    // Beregn pris for brukere og datamaskiner basert på session
    // --------------------------------------------------------------------------------------------------
    public function userAndComputersPrice() {

        // Standardpriser
        $price_normal_datamaskin = 0;
        $price_extra_datamaskin = 149;

        // Servicepakke-spesifikke priser
        if (session('selectedService') == 2) { // Office 365
            $this->basePrice_normal_user = 750;
            $this->basePrice_extra_user = 149;
        } elseif (session('selectedService') == 1) { // Nextcloud
            $this->basePrice_normal_user = 520;
            $this->basePrice_extra_user = 79;
        } else { // Default verdier
            $this->basePrice_normal_user = 219;
            $this->basePrice_extra_user = 0;
        }

        // Beregning av priser
        $this->normalComputersPrice = $this->normalComputers * $price_normal_datamaskin;
        $this->extraComputersPrice = $this->extraComputers * $price_extra_datamaskin;
        $this->normalUsersPrice = $this->normalUsers * $this->basePrice_normal_user;
        $this->basicUsersPrice = $this->basicUsers * $this->basePrice_extra_user;

        // Totalsummer
        $this->sumTottDatamaskiner = $this->normalComputersPrice + $this->extraComputersPrice;
        $this->sumTottUsers = $this->normalUsersPrice + $this->basicUsersPrice;
        $this->sumTott = $this->sumTottDatamaskiner + $this->sumTottUsers + $this->timebankPrice;
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Gjør data tilgjengelig for visningen
    // --------------------------------------------------------------------------------------------------
    public function processOrder()
    {
        // Hent nødvendig data for e-posten
        $orderData = [
            'serviceName' => $this->serviceData->name,
            'amountUsers' => $this->amountUsers,
            'sumTottUsers' => $this->sumTottUsers,
            'amountDatamaskiner' => $this->amountDatamaskiner,
            'sumTottComputers' => $this->sumTottComputers,
            'timebank' => $this->timebank,
            'timebankPrice' => $this->timebankPrice,
            'sumTott' => $this->sumTott,
            'cpu' => $this->cpu ?? 'N/A',
            'ram' => $this->ram ?? 'N/A',
            'storage' => $this->storage ?? 'N/A',
        ];

        return redirect()->route('tdsalgsskjema.FindCustomerForm'); // Erstatt med riktig rute
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Gjør data tilgjengelig for visningen
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.serviceavtaleConfigForm');
    }
}
