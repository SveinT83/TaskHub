<?php

namespace Tronderdata\Skeleton\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SkeletonController extends Controller
{
    public function index()
    {
        return view('Skeleton::index');
    }
}
