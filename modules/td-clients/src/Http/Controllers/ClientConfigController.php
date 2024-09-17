<?php
namespace tronderdata\TdClients\Http\Controllers;

use App\Http\Controllers\Controller;

class ClientConfigController extends Controller
{
    public function index()
    {
        return view('tdclients::admin.config');
    }
}
