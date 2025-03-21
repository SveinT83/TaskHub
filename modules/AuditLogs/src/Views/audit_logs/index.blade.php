@extends('core::layouts.app')

@section('content')
<h1>Audit Logs</h1>

<table>
    <tr>
        <th>User</th>
        <th>Action</th>
        <th>Model Type</th>
        <th>Model ID</th>
        <th>Changes</th>
        <th>Date</th>
    </tr>
    @foreach($logs as $log)
    <tr>
        <td>{{ $log->user->name }}</td>
        <td>{{ strtoupper($log->action) }}</td>
        <td>{{ class_basename($log->model_type) }}</td>
        <td>{{ $log->model_id }}</td>
        <td>{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</td>
        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
    </tr>
    @endforeach
</table>
@endsection