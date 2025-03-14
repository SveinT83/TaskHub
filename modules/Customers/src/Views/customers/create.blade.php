@extends('core::layouts.app')

@section('content')
<h1>Add New Customer</h1>

<form action="{{ route('customers.store') }}" method="POST">
    @csrf
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Company (Optional):</label>
    <input type="text" name="company">

    <label>Email:</label>
    <input type="email" name="email" required>

    <button type="submit">Add Customer</button>
</form>
@endsection