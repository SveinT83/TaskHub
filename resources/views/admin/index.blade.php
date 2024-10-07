@extends('layouts.app')

@section('pageHeader')
    <h1>Admin Dashboard</h1>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Welcome to the Admin Panel
                    </div>
                    <div class="card-body">
                        <p>Here you can manage users, roles, permissions, and other configurations.</p>
                        <ul>
                            <li><a href="{{ route('users.index') }}">Manage Users</a></li>
                            <li><a href="{{ route('roles.index') }}">Manage Roles</a></li>
                            <li><a href="{{ route('permissions.create') }}">Manage Permissions</a></li>
                            <li><a href="{{ route('menu.configurations') }}">Manage Menus</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
