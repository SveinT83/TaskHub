<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\kbartickles\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class KbarticklesController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    // Shows the default page of KB Artickles
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Return view
        // -------------------------------------------------
        return view('kbartickles::index');
    }
}