@extends('core::layouts.app')

@section('content')
<h1>Add New Inventory Item</h1>

<form action="{{ route('inventory.store') }}" method="POST">
    @csrf
    <label>Part Number:</label>
    <input type="text" name="part_number" required>

    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Stock Quantity:</label>
    <input type="number" name="stock_quantity" min="0" required>

    <label>Minimum Stock Alert:</label>
    <input type="number" name="min_stock_alert" min="1" required>

    <button type="submit">Add Part</button>
</form>
@endsection