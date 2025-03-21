@extends('core::layouts.app')

@section('content')
<h1>Projects</h1>

@if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('technician'))
    <a href="{{ route('projects.create') }}">Create Project</a>
@endif

<table>
    <tr>
        <th>Project Number</th>
        <th>Name</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    @foreach($projects as $project)
    <tr>
        <td>{{ $project->project_number }}</td>
        <td>{{ $project->name }}</td>
        <td>{{ $project->customer->name }}</td>
        <td>{{ ucfirst($project->status) }}</td>
        <td>
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('technician'))
                <a href="{{ route('projects.edit', $project->id) }}">Edit</a>
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endsection