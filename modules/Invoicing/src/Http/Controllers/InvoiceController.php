<?php

namespace Modules\Invoicing\Http\Controllers;

use Modules\Invoicing\src\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('invoices.index', ['invoices' => Invoice::all()]);
    }

    public function sendToTripletex($id)
    {
        $invoice = Invoice::findOrFail($id);

        $tripletexData = [
            'customer_number' => $invoice->customer->customer_number,
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . $invoice->id,
            'amount' => $invoice->amount,
            'currency' => 'NOK',
        ];

        Http::post('https://tripletex.no/api/v2/invoices', $tripletexData);

        $invoice->update(['status' => 'sent_to_tripletex']);

        return back()->with('success', 'Invoice sent to Tripletex!');
    }
}