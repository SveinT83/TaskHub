@extends('core::layouts.app')

@section('content')
<h1>Use Inventory Part</h1>

<form action="{{ route('inventory.use', $part->id) }}" method="POST">
    @csrf
    <p><strong>Part Name:</strong> {{ $part->name }}</p>
    <p><strong>Stock Available:</strong> {{ $part->stock_quantity }}</p>

    <label>Quantity to Use:</label>
    <input type="number" name="quantity" min="1" max="{{ $part->stock_quantity }}" required>

    <button type="submit">Use Part</button>
</form>
@endsection