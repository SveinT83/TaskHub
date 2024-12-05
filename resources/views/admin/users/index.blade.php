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
                <!-- view/compoments/new-url.blade.php -->
                <x-new-url href="{{ route('users.create') }}">Add</x-new-url>
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

                <!-- Only show the TH if user has permission -->
                @if(auth()->user()->can('superadmin.edit') || auth()->user()->can('superadmin.delete'))
                    <th>Actions</th>
                @endif
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

                    <!-- Only show the TD if user has permission -->
                    @if(auth()->user()->can('superadmin.edit') || auth()->user()->can('superadmin.delete'))
                        <td>

                            <!-- ------------------------------------------------- -->
                            <!-- Edit button if user has permission -->
                            <!-- ------------------------------------------------- -->
                            @can('superadmin.edit')
                                <!-- view/compoments/new-url.blade.php -->
                                <x-edit-url href="{{ route('users.edit', $user->id) }}"></x-edit-url>
                            @endcan

                            <!-- ------------------------------------------------- -->
                            <!-- Delete button if user has permission -->
                            <!-- ------------------------------------------------- -->
                            @can('superadmin.delete')
                                <!-- view/compoments/delete-form.blade.php -->
                                <x-delete-form route="{{ route('users.destroy', $user->id) }}" warning="Are you sure you want to delete the user?"></x-delete-form>
                            @endcan
                        </td>
                    @endif

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
