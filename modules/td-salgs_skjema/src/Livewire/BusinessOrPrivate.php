<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class BusinessOrPrivate extends Component
{

    // -------------------------------------------------
    // VAR
    // -------------------------------------------------
    public $private = false;
    public $business = false;

    public function setPrivate() {

        // -------------------------------------------------
        // Set private var to true
        // -------------------------------------------------
        $this->private = true;

        // -------------------------------------------------
        // Sett session var
        // -------------------------------------------------
        session(['private' => $this->private]);

        // -------------------------------------------------
        // Sett session var
        // -------------------------------------------------
        return redirect()->route('tdsalgsskjema.aLaCarte');
    }

    public function setBusiness() {

        // -------------------------------------------------
        // Set business var to true
        // -------------------------------------------------
        $this->business = true;

        // -------------------------------------------------
        // Sett session var
        // -------------------------------------------------
        session(['business' => $this->business]);

        return redirect()->route('tdsalgsskjema.aLaCarte');
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.BusinessOrPrivate');
    }
}
