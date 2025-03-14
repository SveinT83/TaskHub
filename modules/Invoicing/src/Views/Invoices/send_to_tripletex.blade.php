@extends('core::layouts.app')

@section('content')
<h1>Send Invoice to Tripletex</h1>

<p><strong>Invoice ID:</strong> {{ $invoice->id }}</p>
<p><strong>Customer:</strong> {{ $invoice->customer->name }}</p>
<p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }} NOK</p>

<form action="{{ route('invoices.send', $invoice->id) }}" method="POST">
    @csrf
    <button type="submit">Confirm Send</button>
</form>
@endsection