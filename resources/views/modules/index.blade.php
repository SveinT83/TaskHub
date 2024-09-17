@extends('layouts.app')

@section('pageHeader')
    <h1>Installed Modules</h1>
@endsection

@section('content')
    <div class="container mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Module Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $module)
                    <tr>
                        <td>{{ $module->name }}</td>
                        <td>{{ $module->description }}</td>
                        <td>{{ $module->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <!-- Toggle Activate/Deactivate -->
                            <form action="{{ route('modules.toggle', $module->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm btn-{{ $module->is_active ? 'danger' : 'success' }}">
                                    {{ $module->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <!-- View Module Details Button -->
                            <a href="{{ route('modules.show', $module->slug) }}" class="btn btn-sm btn-info">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
