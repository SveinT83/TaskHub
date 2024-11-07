<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/UserAndRoles/UserController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Manage users">

        <!-- ------------------------------------------------- -->
        <!-- Add user button if user has permission -->
        <!-- ------------------------------------------------- -->
        @can('superadmin.create')
            <div class="col-md-2 mt-1">
                <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Add New User</a>
            </div>
        @endcan

    </x-page-header>
@endsection

@section('content')
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Show the user table  -->
    <!-- ------------------------------------------------- -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <!-- ------------------------------------------------- -->
            <!-- Foreach loop to show all users -->
            <!-- ------------------------------------------------- -->
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                    <td>

                        <!-- ------------------------------------------------- -->
                        <!-- Edit button if user has permission -->
                        <!-- ------------------------------------------------- -->
                        @can('superadmin.edit')
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        @endcan

                        <!-- ------------------------------------------------- -->
                        <!-- Delete button if user has permission -->
                        <!-- ------------------------------------------------- -->
                        @can('superadmin.edit')
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
