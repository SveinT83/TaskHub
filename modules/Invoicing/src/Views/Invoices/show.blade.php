@extends('core::layouts.app')

@section('content')
<h1>Invoice Details</h1>
<p><strong>Invoice Number:</strong> INV-{{ now()->format('Ymd') }}-{{ $invoice->id }}</p>
<p><strong>Customer:</strong> {{ $invoice->customer->name }}</p>
<p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }} NOK</p>
<p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>

@if($invoice->status == 'pending')
    <form action="{{ route('invoices.send', $invoice->id) }}" method="POST">
        @csrf
        <button type="submit">Send to Tripletex</button>
    </form>
@endif
@endsection