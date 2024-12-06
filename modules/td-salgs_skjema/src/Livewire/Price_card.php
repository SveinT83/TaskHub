<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;
use tronderdata\TdSalgsSkjema\Models\ServicePakke;

class Price_card extends Component
{
    // -------------------------------------------------
    // VAR
    // -------------------------------------------------
    
    //Navigation and steps
    public $nextStep;

    //Sessions variables
    public $private = false;
    public $business = false;
    public $amountUsers = false;
    public $amountDatamaskiner = false;
    public $timebank = false;
    public $selectedService = false;

    //Prices
    public $estimatedPrice = 0;




    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // When the component is mounted, set the amount of users
    // --------------------------------------------------------------------------------------------------
    public function mount()
    {
        // -------------------------------------------------
        // Update the session variables
        // -------------------------------------------------
        $this->updateSessionVariables();

        // -------------------------------------------------
        // Update the estimated price
        // -------------------------------------------------
        $this->estimatedPrice();
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - ESTIMATED PRICE
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function estimatedPrice()
    {
        
        // --------------------------------------------------------------------------------------------------
        // BUSINESS
        // --------------------------------------------------------------------------------------------------
        if (session('business') AND !session('amountUsers')) {
            $this->estimatedPrice += 4149;
        }
        
        // -------------------------------------------------
        // Business And amountUsers
        // -------------------------------------------------
        else if (session('business') AND session('amountUsers') AND !session('amountDatamaskiner')) {
            $this->estimatedPrice = (575 * session('amountUsers')) + (100 * session('amountUsers')) + 800;
        }

        // -------------------------------------------------
        // Business And amountUsers AND amountDatamaskiner
        // -------------------------------------------------
        else if (session('business') AND session('amountUsers') AND session('amountDatamaskiner') AND !session('timebank')) {
            $this->estimatedPrice = (575 * session('amountUsers')) + (100 * session('amountDatamaskiner')) + 399;
        }

        // -------------------------------------------------
        // Business And amountUsers AND amountDatamaskiner AND timebank
        // -------------------------------------------------
        else if (session('business') AND session('amountUsers') AND session('amountDatamaskiner') AND session('timebank')) {
            $this->estimatedPrice = (575 * session('amountUsers')) + 100 * session('amountDatamaskiner') + 80 * session('timebank');
        }

        // --------------------------------------------------------------------------------------------------
        // PRIVATE
        // --------------------------------------------------------------------------------------------------
        if (session('private') AND !session('amountUsers')) {
            $this->estimatedPrice += 219;
        }

        // -------------------------------------------------
        // private And amountUsers
        // -------------------------------------------------
        else if (session('private') AND session('amountUsers') AND !session('amountDatamaskiner')) {
            $this->estimatedPrice = (219 * session('amountUsers'));
        }

        // -------------------------------------------------
        // private And amountUsers AND amountDatamaskiner
        // -------------------------------------------------
        else if (session('private') AND session('amountUsers') AND session('amountDatamaskiner')) {

            //If more computers than users
            if (session('amountDatamaskiner') > session('amountUsers')) {
                $this->estimatedPrice = 219 + (149 * session('amountDatamaskiner')) - 149;
            } else {
                $this->estimatedPrice = 219;
            }
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - ESTIMATED PRICE
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function updateSessionVariables()
    {
        // -------------------------------------------------
        // Private
        // -------------------------------------------------
        if (session('private')) {
            $this->private = true;

        // -------------------------------------------------
        // Business
        // -------------------------------------------------
        } elseif (session('business')) {
            $this->business = true;
        }

        // -------------------------------------------------
        // amountUsers
        // -------------------------------------------------
        elseif (session('amountUsers')) {
            $this->amountUsers = session('amountUsers');
        }

        // -------------------------------------------------
        // timebank
        // -------------------------------------------------
        elseif (session('timebank')) {
            $this->timebank = session('timebank');
        }

        // -------------------------------------------------
        // selectedService
        // -------------------------------------------------
        elseif (session('selectedService')) {
            $this->selectedService = session('selectedService');
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - REFRESH
    // --------------------------------------------------------------------------------------------------
    // Refresh the component
    // --------------------------------------------------------------------------------------------------
    public function refresh()
    {
        
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the component
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.Price_card');
    }
}
