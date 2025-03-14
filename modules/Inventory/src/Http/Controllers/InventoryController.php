<?php

namespace Modules\Inventory\Http\Controllers;

use Modules\Inventory\src\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mail;
use Modules\Notifications\src\Mail\LowStockAlertMail;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.index', ['inventory' => Inventory::all()]);
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_number' => 'required|unique:inventory,part_number',
            'name' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:1',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Part added to inventory!');
    }

    public function usePart(Request $request, $id)
    {
        $part = Inventory::findOrFail($id);

        if ($part->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $part->decrement('stock_quantity', $request->quantity);

        if ($part->stock_quantity <= $part->min_stock_alert) {
            Mail::to('manager@example.com')->send(new LowStockAlertMail($part));
        }

        return back()->with('success', 'Part used successfully.');
    }
}