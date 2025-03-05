<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace TronderData\Categories\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CategoriesController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    // Shows the default page of the categories module
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Return view
        // -------------------------------------------------
        return view('categories::index');
    }
}