@extends('core::layouts.app')

@section('content')
<h1>Invoices</h1>
<a href="{{ route('invoices.index') }}">View All Invoices</a>

<table>
    <tr>
        <th>Invoice Number</th>
        <th>Customer</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    @foreach($invoices as $invoice)
    <tr>
        <td>{{ 'INV-' . now()->format('Ymd') . '-' . $invoice->id }}</td>
        <td>{{ $invoice->customer->name }}</td>
        <td>{{ number_format($invoice->amount, 2) }} NOK</td>
        <td>{{ ucfirst($invoice->status) }}</td>
        <td>
            <a href="{{ route('invoices.show', $invoice->id) }}">View</a>
            @if($invoice->status == 'pending')
                <form action="{{ route('invoices.send', $invoice->id) }}" method="POST">
                    @csrf
                    <button type="submit">Send to Tripletex</button>
                </form>
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endsection