<?php

namespace Modules\Customers\Http\Controllers;

use Modules\Customers\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers::index', ['customers' => Customer::all()]);
    }

    public function create()
    {
        return view('customers::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'company' => 'nullable|string',
            'email' => 'required|email|unique:customers,email',
        ]);

        Customer::create([
            'customer_number' => 'CUST-' . strtoupper(Str::random(6)),
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added!');
    }
}