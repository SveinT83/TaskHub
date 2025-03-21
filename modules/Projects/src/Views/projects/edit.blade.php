@extends('core::layouts.app')

@section('content')
<h1>Edit Project</h1>

<form action="{{ route('projects.update', $project->id) }}" method="POST">
    @csrf
    @method('PUT')

    <p><strong>Project Number:</strong> {{ $project->project_number }}</p>

    <label>Project Description:</label>
    <textarea name="description">{{ old('description', $project->description) }}</textarea>

    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
        <label>Select Customer:</label>
        <select name="customer_id">
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" @if($customer->id == $project->customer_id) selected @endif>
                    {{ $customer->name }} ({{ $customer->company ?? 'No Company' }})
                </option>
            @endforeach
        </select>

        <label>Change Project Status:</label>
        <select name="status">
            <option value="pending" @if($project->status == 'pending') selected @endif>Pending</option>
            <option value="approved" @if($project->status == 'approved') selected @endif>Approved</option>
            <option value="rejected" @if($project->status == 'rejected') selected @endif>Rejected</option>
        </select>
    @else
        <p><strong>Status:</strong> {{ ucfirst($project->status) }}</p>
        <p><strong>Customer:</strong> {{ $project->customer->name }} ({{ $project->customer->company ?? 'No Company' }})</p>
    @endif

    <button type="submit">Update Project</button>
</form>
@endsection