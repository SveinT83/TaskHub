@extends('layouts.app')

@section('title', 'User Profile')

@section('pageHeader')
    <div class="row align-items-center justify-content-between">
        <h1 class="col-md-6">{{ $user->first_name }} {{ $user->last_name }}'s Profile</h1>

        <div class="col-md-2">
            <div class="row justify-content-evenly">
                <!-- Edit-knapp -->
                <div class="col-lg-5 d-none d-lg-block m-1">
                    <div class="row">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm me-2 bi bi-pencil"></a>
                    </div>
                </div>
                
                <!-- Delete-knapp med bekreftelse -->
                <form class="col-lg-5 d-none d-lg-block m-1" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <div class="row">
                        <button type="submit" class="btn btn-danger btn-sm bi bi-trash3" onclick="return confirm('Are you sure you want to delete this client?')"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mt-3">

        <div class="card">
            <div class="card-header">
                <h2>User Information</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">First Name:</td>
                            <td>
                                {{ $user->first_name }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Last Name:</td>
                            <td>
                                {{ $user->last_name }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Email:</td>
                            <td>
                                {{ $user->email }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Phone:</td>
                            <td>
                                {{ $user->phone }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Profile action's -->
        <!-- ------------------------------------------------- -->
        <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
            <div class="col-lg-2 m-2">
                <div class="row">
                    <a href="{{ route('client.users.index') }}" class="btn btn-secondary  bi bi-backspace"> Back to Users</a>
                </div>
            </div>

            <!-- Edit-knapp -->
            <div class="col-lg-2 m-2">
                <div class="row">
                    <a href="{{ route('client.users.edit', $user->id) }}" class="btn btn-primary btn bi bi-pencil"> Edit</a>
                </div>
            </div>

            <!-- Delete-knapp med bekreftelse -->
            <form class="col-lg-2 m-2" action="{{ route('client.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <div class="row">
                    <button type="submit" class="btn btn-danger btn bi bi-trash3" onclick="return confirm('Are you sure you want to delete this client?')"> Delete</button>
                </div>
            </form>
        </div>
    </div>
@endsection
