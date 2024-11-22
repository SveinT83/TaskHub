<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use function session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;

class antallDatamaskiner extends Component
{

    // -------------------------------------------------
    // VAR
    // -------------------------------------------------
    public $amount;



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // When the component is mounted, set the amount of users
    // --------------------------------------------------------------------------------------------------
    public function mount()
    {
        // -------------------------------------------------
        // Set number of computers based on the number of users
        // -------------------------------------------------
        $this->amount = $this->pb();
    }




    // --------------------------------------------------------------------------------------------------
    // FUNCTION - PB
    // --------------------------------------------------------------------------------------------------
    // This functions checks if the session that holds the amount of users exists
    // If it does, it returns the amount of users, if not, it returns 1 user
    // The value is used to set the amount of computers
    // --------------------------------------------------------------------------------------------------
    public function pb() {

        // -------------------------------------------------
        // If session that holds the amount of users exists
        // -------------------------------------------------
        if (session('amountUsers')) {

            // -------------------------------------------------
            // Return the amount of users
            // -------------------------------------------------
            return session('amountUsers');

        } else {

            // -------------------------------------------------
            // If unknown, return 1 user
            // -------------------------------------------------
            return "1";
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE AMOUNT USERS
    // --------------------------------------------------------------------------------------------------
    // Save the volume of users to the session and redirect to the next form
    // --------------------------------------------------------------------------------------------------
    public function updateAmountDatamaskiner()
    {
        // -------------------------------------------------
        // Save the value to the session
        // -------------------------------------------------
        session(['amountDatamaskiner' => $this->amount]);

        // -------------------------------------------------
        // If session is Bussiness then redirect to the
        // Office 365 of Nextcloud form
        // -------------------------------------------------
        if (session('business')) {

            // -------------------------------------------------
            // Redirect to the next form - Nextcloud or Office 365
            // -------------------------------------------------
            return redirect()->route('tdsalgsskjema.antallTimer');

        }

        // -------------------------------------------------
        // Redirect to the service agreement form
        // -------------------------------------------------
        return redirect()->route('tdsalgsskjema.serviceavtale');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the view whih the amount of users
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.antallDatamaskiner', [
            'pb' => $this->pb()
        ]);
    }
}