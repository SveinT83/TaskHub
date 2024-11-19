<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;
use tronderdata\TdSalgsSkjema\Models\ServicePakke;

class ServiceavtaleConfigForm extends Component
{

    // -------------------------------------------------
    // VAR
    // -------------------------------------------------
    public $private = false;
    public $business = false;
    public $amount = 0;
    public $amountUsers = 0;
    public $amountDatamaskiner = 0;
    public $normalUsers = 0;
    public $basicUsers = 0;
    public $extraComputers = 0;
    public $acResult = 0;
    public $serviceData = false;



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // Mount the component
    // --------------------------------------------------------------------------------------------------
    public function mount() {

        // -------------------------------------------------
        // Private or business
        // -------------------------------------------------
        if (session('private')) {
            $this->private = session('private');
        } elseif (session('business')) {
            $this->business = session('business');
        }

        // -------------------------------------------------
        // Service avtale
        // -------------------------------------------------
        if (session('selectedService')) {
            $servicePakke = ServicePakke::where('id', session('selectedService'))->first();

            $this->serviceData = $this->servicePakkeData($servicePakke);

        } else {
            return redirect()->route('tdsalgsskjema.serviceavtale');
        }

        // -------------------------------------------------
        // If the amount of users is not set, redirect to
        // -------------------------------------------------
        if (session('amountUsers')) {
            $this->amountUsers = session('amountUsers');
        } else {
            return redirect()->route('tdsalgsskjema.amountUsers');
        }

        // -------------------------------------------------
        // If the amount of computers is not set, redirect to
        // -------------------------------------------------
        if (session('amountDatamaskiner')) {
            $this->amountDatamaskiner = session('amountDatamaskiner');
        }else {
            return redirect()->route('tdsalgsskjema.amountDatamaskiner');
        }

        // -------------------------------------------------
        // Se if a computer is an extra computer
        // -------------------------------------------------
        $this->acResult = $this->ac();

    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SERVICEPAKKEDATA
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function servicePakkeData($servicePakke) {

        $this->serviceName = $servicePakke->name;
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - AC
    // --------------------------------------------------------------------------------------------------
    // Calculate the amount of extra computers and return the result
    // --------------------------------------------------------------------------------------------------
    public function ac() {

        // -------------------------------------------------
        // If there is more computers than users
        // -------------------------------------------------
        if (session('amountDatamaskiner') > session('amountUsers')) {

            // -------------------------------------------------
            // If there is more computers than users
            // -------------------------------------------------
            $this->extraComputers = session('amountDatamaskiner') - session('amountUsers');

        }

    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the view
    // --------------------------------------------------------------------------------------------------
    public function render()
    {

        return view('TdSalgsSkjema::livewire.serviceavtaleConfigForm');
    }
}