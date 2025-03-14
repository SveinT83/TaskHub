@extends('core::layouts.app')

@section('content')
<h1>Create a New Project</h1>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf
    <label>Select Customer:</label>
    <select name="customer_id">
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->company ?? 'No Company' }})</option>
        @endforeach
    </select>

    <button type="submit">Create Project</button>
</form>
@endsection