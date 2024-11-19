<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use tronderdata\TdSalgsSkjema\Models\ServicePakke;

class ServiceavtaleForm extends Component
{
    public $servicePakker;



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // Mount the component and get the service packages
    // --------------------------------------------------------------------------------------------------
    public function mount()
    {

        // -------------------------------------------------
        // Get the service packages based on the session
        // -------------------------------------------------
        if (session('private')) {
            $this->servicePakker = ServicePakke::where('private', true)->where('is_enabled', true)->get();
        } else {
            $this->servicePakker = ServicePakke::where('private', null)->where('is_enabled', true)->get();
        }
    }




    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SET SERVICE
    // --------------------------------------------------------------------------------------------------
    public function setService($serviceId)
    {
        // Hent servicepakken basert på ID
        $selectedService = \tronderdata\TdSalgsSkjema\Models\ServicePakke::with('alacarteItems')->find($serviceId);

        if ($selectedService) {
            // Lag en session med alacarte-elementer og deres standardantall
            $quantities = [];

            // Hent antall brukere fra session, eller sett til standardverdi
            $amountUsers = session('amountUsers', session('business') ? 5 : (session('private') ? 1 : 1));

            foreach ($selectedService->alacarteItems as $item) {
                switch ($item->pr) {
                    case 'enhet':
                    case 'user':
                        $quantities[$item->id] = $amountUsers; // Sett antall basert på brukere
                        break;
                    case 'client':
                    case 'stk':
                    default:
                        $quantities[$item->id] = 1; // Sett standard til 1
                        break;
                }
            }

            session([
                'selectedService' => $selectedService->toArray(), // Lagre servicepakke som array
                'quantities' => $quantities, // Lagre antall i session
            ]);

            // Naviger til antall datamaskiner
            return redirect()->route('tdsalgsskjema.antallDatamaskiner');
        }

        // Hvis ingen servicepakke ble funnet, gi tilbakemelding
        session()->flash('error', 'Servicepakke ikke funnet.');
    }




    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the view whit the service packages
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.ServiceavtaleForm', [
            'servicePakker' => $this->servicePakker,
        ]);
    }
}
