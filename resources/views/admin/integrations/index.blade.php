<!-- filepath: /var/Projects/TaskHub/Dev/resources/views/admin/integrations/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Integrations</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Active</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($integrations as $integration)
            <tr>
                <td>{{ $integration->id }}</td>
                <td>{{ $integration->name }}</td>
                <td>{{ $integration->active ? 'Yes' : 'No' }}</td>
                <td>{{ $integration->updated_at }}</td>
                <td>
                    @if ($integration->active)
                        <form action="{{ route('admin.integrations.deactivate', $integration->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">Deactivate</button>
                        </form>
                    @else
                        <form action="{{ route('admin.integrations.activate', $integration->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Activate</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection