@extends('core::layouts.app')

@section('content')
<h1>Customers</h1>
<a href="{{ route('customers.create') }}">Add Customer</a>

<table>
    <tr>
        <th>Customer Number</th>
        <th>Name</th>
        <th>Company</th>
        <th>Email</th>
    </tr>
    @foreach($customers as $customer)
    <tr>
        <td>{{ $customer->customer_number }}</td>
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->company ?? 'N/A' }}</td>
        <td>{{ $customer->email }}</td>
    </tr>
    @endforeach
</table>
@endsection