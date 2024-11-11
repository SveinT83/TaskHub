<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/UserAndRoles/RoleController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <h1>Edit role</h1>
@endsection

@section('content')
<div class="container mt-3">

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ------------------------------------------------- -->
        <!-- Put the new user form in a card -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="Edit role form">

            <!-- ------------------------------------------------- -->
            <!-- Role name -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Permissions -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="permissions" class="form-label">Permissions</label>
                <select multiple class="form-control" id="permissions" name="permissions[]">

                    <!-- ------------------------------------------------- -->
                    <!-- Foreach loop to show all permissions -->
                    <!-- ------------------------------------------------- -->
                    @foreach ($permissions as $permission)
                        <option value="{{ $permission->name }}" {{ $role->permissions->contains($permission) ? 'selected' : '' }}>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row m-1">
                <!-- view/compoments/save-update-button.blade.php -->
                <x-save-update-button type="submit">Update role</x-save-update-button>
            </div>

        </x-card-secondary>
    </form>
</div>
@endsection
