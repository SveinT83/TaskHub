<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Admin dashboard view
    public function index()
    {
        return view('admin.index');
    }
}
