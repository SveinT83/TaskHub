<?php

namespace Modules\Clients\default\Controllers;

use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index()
    {
        return view('clients::index');
    }
}