<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Henter den innloggede brukeren
        $user = auth()->user();

        return view('dashboard');
    }
}
