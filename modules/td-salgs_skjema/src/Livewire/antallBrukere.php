<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use function session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;

class antallBrukere extends Component
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
        // pb = private or business
        // -------------------------------------------------
        $this->amount = $this->pb();
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE AMOUNT USERS
    // --------------------------------------------------------------------------------------------------
    // Save the volume of users to the session and redirect to the next form
    // --------------------------------------------------------------------------------------------------
    public function updateAmountUsers()
    {
        // -------------------------------------------------
        // Save the value to the session
        // -------------------------------------------------
        session(['amountUsers' => $this->amount]);

        // -------------------------------------------------
        // If session is Bussiness then redirect to the
        // Office 365 of Nextcloud form
        // -------------------------------------------------
        if (session('business')) {

            // -------------------------------------------------
            // Redirect to the next form
            // -------------------------------------------------
            return redirect()->route('tdsalgsskjema.aLaCarte');

        }

        // -------------------------------------------------
        // Redirect to the a la carte form
        // -------------------------------------------------
        return redirect()->route('tdsalgsskjema.aLaCarte');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - PB
    // --------------------------------------------------------------------------------------------------
    // If private or business is set, return the amount of users to prefill the form
    // --------------------------------------------------------------------------------------------------
    public function pb() {

        // -------------------------------------------------
        // If private or business is set
        // -------------------------------------------------
        if (session('private')) {

            // -------------------------------------------------
            // Private has one default user
            // -------------------------------------------------
            return "1";

        } else if (session('business')) {

            // -------------------------------------------------
            // Business has 5 default users
            // -------------------------------------------------
            return "5";

        } else {

            // -------------------------------------------------
            // If unknown, return 1 user
            // -------------------------------------------------
            return "1";
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the view whih the amount of users
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.antallBrukere', [
            'pb' => $this->pb()
        ]);
    }
}