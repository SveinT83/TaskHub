<?php

namespace tronderdata\Skeleton\Http\Controllers;

use App\Http\Controllers\Controller;

class SkeletonController extends Controller
{
    public function index()
    {
        return view('Skeleton::index');
    }
}
