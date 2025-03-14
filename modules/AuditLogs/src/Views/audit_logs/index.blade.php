@extends('layouts.app')

@section('content')
<h1>Audit Log</h1>

<table>
    <tr>
        <th>User</th>
        <th>Entity</th>
        <th>Action</th>
        <th>Old Value</th>
        <th>New Value</th>
        <th>Timestamp</th>
    </tr>
    @foreach($logs as $log)
    <tr>
        <td>{{ $log->user->name }}</td>
        <td>{{ $log->entity_type }} (ID: {{ $log->entity_id }})</td>
        <td>{{ ucfirst($log->action) }}</td>
        <td>{{ json_encode($log->old_value) }}</td>
        <td>{{ json_encode($log->new_value) }}</td>
        <td>{{ $log->created_at }}</td>
    </tr>
    @endforeach
</table>
@endsection