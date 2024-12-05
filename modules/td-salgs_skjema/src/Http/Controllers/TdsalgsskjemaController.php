<?php

namespace tronderdata\TdSalgsSkjema\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TdsalgsskjemaController extends Controller
{
    // -------------------------------------------------------------------
    // FUNCTION - INDEX
    // -------------------------------------------------------------------
    public function index(Request $request)
    {

        return view('TdSalgsSkjema::index');
    }

    // -------------------------------------------------------------------
    // FUNCTION - CREATE
    // -------------------------------------------------------------------
    public function create(Request $request)
    {

        return view('TdSalgsSkjema::forms.create');
    }

    // -------------------------------------------------------------------
    // FUNCTION - ANTALL BRUKERE
    // -------------------------------------------------------------------
    public function antallBrukere(Request $request)
    {

        return view('TdSalgsSkjema::forms.antallBrukere');
    }

    // -------------------------------------------------------------------
    // FUNCTION - ANTALL DATAMASKINER
    // -------------------------------------------------------------------
    public function antallDatamaskiner(Request $request)
    {

        return view('TdSalgsSkjema::forms.antallDatamaskiner');
    }

    // -------------------------------------------------------------------
    // FUNCTION - ANTALL TIMER
    // -------------------------------------------------------------------
    public function antallTimer(Request $request)
    {

        return view('TdSalgsSkjema::forms.antallTimer');
    }

    // -------------------------------------------------------------------
    // FUNCTION - SERVICEAVTALE
    // -------------------------------------------------------------------
    public function serviceavtale(Request $request)
    {

        return view('TdSalgsSkjema::forms.serviceavtale');
    }

    // -------------------------------------------------------------------
    // FUNCTION - SERVICEAVTALE CONFIG
    // -------------------------------------------------------------------
    public function serviceavtaleConfig(Request $request)
    {

        return view('TdSalgsSkjema::forms.serviceavtaleConfig');
    }

    // -------------------------------------------------------------------
    // FUNCTION - A LA CARTE
    // -------------------------------------------------------------------
    public function aLaCarte(Request $request)
    {

        return view('TdSalgsSkjema::forms.aLaCarte');
    }

    // -------------------------------------------------------------------
    // FUNCTION - FIND CUSTOMER FORM
    // -------------------------------------------------------------------
    public function FindCustomerForm(Request $request)
    {

        return view('TdSalgsSkjema::forms.FindCustomerForm');
    }

    // -------------------------------------------------------------------
    // FUNCTION - REGISTER NEW CUSTOMER
    // -------------------------------------------------------------------
    public function RegisterNewCustomer(Request $request)
    {

        return view('TdSalgsSkjema::forms.RegisterNewCustomer');
    }
}
