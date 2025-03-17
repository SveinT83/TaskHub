<?php

namespace Modules\Projects\Http\Controllers;

use Modules\Projects\Models\Project;
use Modules\Customers\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects::index', ['projects' => Project::with('customer')->get()]); // ✅ Matches namespace
    }

    public function create()
    {
        return view('projects::create', ['customers' => Customer::all()]); // ✅ Matches namespace
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        Project::create([
            'project_number' => 'P-' . now()->format('Ymd') . '-' . strtoupper(\Str::random(4)),
            'customer_id' => $request->customer_id,
            'status' => 'pending',
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created!');
    }
}