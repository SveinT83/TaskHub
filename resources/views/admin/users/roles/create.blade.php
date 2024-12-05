<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/UserAndRoles/UserController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->
@extends('layouts.app')

@section('pageHeader')
    <h1>Create a new role</h1>
@endsection

@section('content')
<div class="container mt-3">

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <!-- ------------------------------------------------- -->
        <!-- Put the new role form in a card -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="New role form">

            <!-- ------------------------------------------------- -->
            <!-- Role name - Required -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Permissions - Required -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="permissions" class="form-label">Permissions</label>
                <select multiple class="form-control" id="permissions" name="permissions[]">

                    <!-- ------------------------------------------------- -->
                    <!-- Loop through all permissions -->
                    <!-- ------------------------------------------------- -->
                    @foreach ($permissions as $permission)
                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Create user button -->
            <!-- ------------------------------------------------- -->
            <div class="row m-1">
                <!-- view/compoments/save-update-button.blade.php -->
                <x-save-update-button type="submit" ico="bi bi-floppy">Create role</x-save-update-button>
            </div>

        </x-card-secondary>

    </form>
</div>
@endsection
