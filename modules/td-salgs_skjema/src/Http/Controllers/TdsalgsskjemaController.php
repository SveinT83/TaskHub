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
    // FUNCTION - BUSINESS OR PRIVATE
    // -------------------------------------------------------------------
    public function businessOrPrivate(Request $request)
    {

        return view('TdSalgsSkjema::forms.businessOrPrivate');
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
}
