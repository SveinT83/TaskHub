<?php

/*
Salgs skjema

1: Privat eller Bedrift?
2: Antall brukere
	Hvis Bedrift:
		1: Office 365 eller Nextcloud
		2: Ala Carte - prefyllt basert på service avtale
			Hvis Office: Microsoft 365 Business Premium, Managed EDR (Antivirus), Managed Patch, Managed Web protection, Web Hotell - Startpakke, Timebank 05
			Hvis Nextcloud: NextCloud VM, Managed EDR (Antivirus), Managed Patch, Managed Web protection, Web Hotell - Startpakke, Timebank 05
	Hvis privat
		1: Ala Carte - prefyllt med Timebank 12, Managed Antivirus og Managed Web Protection
*/

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class BussinessOrPrivate extends Component
{

    // -------------------------------------------------
    // VAR
    // -------------------------------------------------
    public $private = false;
    public $business = false;

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SET PRIVATE
    // --------------------------------------------------------------------------------------------------
    // If private is chosen, set the session var to private
    // --------------------------------------------------------------------------------------------------
    public function setPrivate() {

        // -------------------------------------------------
        // Set private var to true
        // -------------------------------------------------
        $this->private = true;

        // -------------------------------------------------
        // Sett session var
        // -------------------------------------------------
        session(['private' => $this->private]);
        session()->forget('business');

        // -------------------------------------------------
        // Redirect to the next form
        // -------------------------------------------------
        return redirect()->route('tdsalgsskjema.antallBrukere');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SET BUSINESS
    // --------------------------------------------------------------------------------------------------
    // If private is chosen, set the session var to private
    // --------------------------------------------------------------------------------------------------
    public function setBusiness() {

        // -------------------------------------------------
        // Set business var to true
        // -------------------------------------------------
        $this->business = true;

        // -------------------------------------------------
        // Sett session var
        // -------------------------------------------------
        session(['business' => $this->business]);
        session()->forget('private');

        // -------------------------------------------------
        // Redirect to the next form
        // -------------------------------------------------
        return redirect()->route('tdsalgsskjema.antallBrukere');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the view
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.BussinessOrPrivate');
    }
}
