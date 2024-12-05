<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/UserAndRoles/UserController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Create User"></x-page-header>
@endsection

@section('content')
<div class="container">

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <!-- ------------------------------------------------- -->
        <!-- Put the new user form in a card -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="New user form">

            <!-- ------------------------------------------------- -->
            <!-- Name - Required -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Email - Required -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Password - Required -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Confirm Password - Required -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Roles - Required -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="roles">Roles</label>
                <select name="roles[]" class="form-control" multiple required>

                    <!-- ------------------------------------------------- -->
                    <!-- Create an option for each role -->
                    <!-- ------------------------------------------------- -->
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>


            <!-- ------------------------------------------------- -->
            <!-- Create user button -->
            <!-- ------------------------------------------------- -->
            <div class="row m-1">
                <!-- view/compoments/save-update-button.blade.php -->
                <x-save-update-button type="submit">Create User</x-save-update-button>
            </div>

        </x-card-secondary>
    </form>
</div>
@endsection
