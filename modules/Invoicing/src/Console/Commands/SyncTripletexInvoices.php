<?php

namespace Modules\Invoicing\Console\Commands;

use Illuminate\Console\Command;
use Modules\Invoicing\Models\Invoice;
use Illuminate\Support\Facades\Http;

class SyncTripletexInvoices extends Command
{
    protected $signature = 'tripletex:sync-invoices';
    protected $description = 'Send pending invoices to Tripletex.';

    public function handle()
    {
        $invoices = Invoice::where('status', 'pending')->get();

        foreach ($invoices as $invoice) {
            $response = Http::post('https://tripletex.no/api/v2/invoices', [
                'customer_number' => $invoice->customer->customer_number,
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . $invoice->id,
                'amount' => $invoice->amount,
                'currency' => 'NOK',
            ]);

            if ($response->successful()) {
                $invoice->update(['status' => 'sent_to_tripletex']);
            }
        }

        $this->info("Invoices sent to Tripletex.");
    }
}