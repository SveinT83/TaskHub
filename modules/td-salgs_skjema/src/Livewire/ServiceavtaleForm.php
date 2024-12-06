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
            $this->servicePakker = ServicePakke::where('private', 0)->where('is_enabled', true)->get();
        }
    }




    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SET SERVICE
    // --------------------------------------------------------------------------------------------------
    public function setService($serviceId)
    {

        // -------------------------------------------------
        // Get the service basedf on the service id fore validating
        // -------------------------------------------------
        $selectedService = \tronderdata\TdSalgsSkjema\Models\ServicePakke::find($serviceId);

        // -------------------------------------------------
        // If the service is a valid ID
        // -------------------------------------------------
        if ($selectedService) {

            // -------------------------------------------------
            // Set the selected service in the session
            // -------------------------------------------------
            session(['selectedService' => $serviceId]);

            // -------------------------------------------------
            // Redirect to the next step
            // -------------------------------------------------
            return redirect()->route('tdsalgsskjema.serviceavtaleConfig');

        } else {

            // -------------------------------------------------
            // Flash an error message
            // -------------------------------------------------
            session()->flash('error', 'Servicepakke ikke funnet.');
        }
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
