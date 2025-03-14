@extends('core::layouts.app')

@section('content')
<h1>Projects</h1>
<a href="{{ route('projects.create') }}">Create Project</a>

<table>
    <tr>
        <th>Project Number</th>
        <th>Customer</th>
        <th>Status</th>
    </tr>
    @foreach($projects as $project)
    <tr>
        <td>{{ $project->project_number }}</td>
        <td>{{ $project->customer->name }}</td>
        <td>{{ ucfirst($project->status) }}</td>
    </tr>
    @endforeach
</table>
@endsection