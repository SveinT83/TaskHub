<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/UserAndRoles/RoleController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

@section('title', 'Home')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <!-- view/compoments/page-header.blade.php -->
    <x-page-header pageHeaderTitle="Roles and premissions"></x-page-header>
@endsection

@section('content')
<div class="container mt-3">

    <div class="row">
        <div class="col-md-12">

            <!-- ------------------------------------------------- -->
            <!-- ROLES -->
            <!-- Card - view/compoments/card-secondary.blade.php -->
            <!-- ------------------------------------------------- -->
            <x-card-secondary title="Roles">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- ------------------------------------------------- -->
                        <!-- Loop through each role -->
                        <!-- ------------------------------------------------- -->
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>

                                <!-- ------------------------------------------------- -->
                                <!-- Edit role button if user has permission -->
                                <!-- ------------------------------------------------- -->
                                @can('superadmin.edit')
                                    <!-- view/compoments/edit-url.blade.php -->
                                    <x-edit-url href="{{ route('roles.edit', $role->id) }}"></x-edit-url>
                                @endcan

                                <!-- ------------------------------------------------- -->
                                <!-- Delete role form if user has permission -->
                                <!-- ------------------------------------------------- -->
                                @can('superadmin.delete')
                                    <!-- view/compoments/delete-form.blade.php -->
                                    <x-delete-form route="{{ route('roles.destroy', $role->id) }}"></x-delete-form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ------------------------------------------------- -->
                <!-- Add role button if user has permission -->
                <!-- ------------------------------------------------- -->
                @can('superadmin.create')
                    <!-- view/compoments/new-url.blade.php -->
                    <x-new-url href="{{ route('roles.create') }}">Add</x-new-url>
                @endcan
            </x-card-secondary>

        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">

            <!-- ------------------------------------------------- -->
            <!-- PERMISSIONS -->
            <!-- Card - view/compoments/card-secondary.blade.php -->
            <!-- ------------------------------------------------- -->
            <x-card-secondary title="Permissions">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- ------------------------------------------------- -->
                        <!-- Loop through each permission -->
                        <!-- ------------------------------------------------- -->
                        @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <!-- ------------------------------------------------- -->
                                <!-- Edit role button if user has permission -->
                                <!-- ------------------------------------------------- -->
                                @can('superadmin.edit')
                                    <!-- view/compoments/edit-url.blade.php -->
                                    <x-edit-url href="{{ route('permissions.edit', $permission->id) }}"></x-edit-url>
                                @endcan

                                <!-- ------------------------------------------------- -->
                                <!-- Delete role form if user has permission -->
                                <!-- ------------------------------------------------- -->
                                @can('superadmin.delete')
                                    <!-- view/compoments/delete-form.blade.php -->
                                    <x-delete-form route="{{ route('permissions.destroy', $permission->id) }}"></x-delete-form>
                                @endcan

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ------------------------------------------------- -->
                <!-- Add role button if user has permission -->
                <!-- ------------------------------------------------- -->
                @can('superadmin.create')
                    <!-- view/compoments/new-url.blade.php -->
                    <x-new-url href="{{ route('permissions.create') }}">Add</x-new-url>
                @endcan
            </x-card-secondary>

        </div>
    </div>

</div>
@endsection
