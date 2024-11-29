<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/UserAndRoles/UserController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Edit User: {{ $user->name }}"></x-page-header>
@endsection

@section('content')
<div class="container mt-3">

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ------------------------------------------------- -->
        <!-- Put the edit user form in a card -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="Edit user form">

            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password (leave blank to keep current password)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="roles">Roles</label>
                <select name="roles[]" class="form-control" multiple required>

                    <!-- ------------------------------------------------- -->
                    <!-- Create an option for each role -->
                    <!-- ------------------------------------------------- -->
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @if(in_array($role->name, $userRoles)) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Update user button -->
            <!-- ------------------------------------------------- -->
            <div class="row m-1">
                <!-- view/compoments/save-update-button.blade.php -->
                <x-save-update-button type="submit">Update</x-save-update-button>
            </div>

        </x-card-secondary>
    </form>
</div>
@endsection
