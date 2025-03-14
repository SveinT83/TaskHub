@extends('core::layouts.app')

@section('content')
<h1>Inventory</h1>
<a href="{{ route('inventory.create') }}">Add New Inventory Item</a>

<table>
    <tr>
        <th>Part Number</th>
        <th>Name</th>
        <th>Stock Quantity</th>
        <th>Minimum Alert</th>
        <th>Actions</th>
    </tr>
    @foreach($inventory as $part)
    <tr>
        <td>{{ $part->part_number }}</td>
        <td>{{ $part->name }}</td>
        <td>{{ $part->stock_quantity }}</td>
        <td>{{ $part->min_stock_alert }}</td>
        <td>
            <a href="{{ route('inventory.use', $part->id) }}">Use Part</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection