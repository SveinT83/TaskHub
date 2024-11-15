@extends('layouts.app')

@section('title', 'Client Profile')

@section('pageHeader')
    <div class="row align-items-center justify-content-between">
        <h1 class="col-md-6">{{ $client->name }}'s Profile</h1>

        <div class="col-md-2">
            <div class="row justify-content-evenly">
                <!-- Edit-knapp -->
                <div class="col-lg-5 d-none d-lg-block m-1">
                    <div class="row">
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm me-2 bi bi-pencil"></a>
                    </div>
                </div>
                
                <!-- Delete-knapp med bekreftelse -->
                <form class="col-lg-5 d-none d-lg-block m-1" action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline-block;">
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
        <div class="row">

            <!-- ------------------------------------------------- -->
            <!-- Client info -->
            <!-- ------------------------------------------------- -->
            <div class="col-lg-4 mt-2">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header">
                        <h2>Client Details</h2>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</td>
                                    <td>
                                        {{ $client->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Email:</td>
                                    <td>
                                        {{ $client->main_email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">VAT Number:</td>
                                    <td>
                                        {{ $client->vat_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Account Number:</td>
                                    <td>
                                        {{ $client->account_number }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Edit-knapp -->
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary  btn-sm me-2 bi bi-pencil"> Edit</a>
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Sites -->
            <!-- ------------------------------------------------- -->
            <div class="col-lg-4 mt-2">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header">
                        <h2>Sites</h2>
                    </div>
                    <div class="card-body">
                        @if ($sites->isEmpty())
                            <p>No sites found.</p>
                        @else
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Site Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sites as $site)
                                        <tr>
                                            <th scope="row">{{ $site->name }}</td>
                                            <td>
                                                <a href="{{ route('client.sites.profile', $site->id) }}" class="btn btn-primary btn-sm bi bi-binoculars"> View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Users -->
            <!-- ------------------------------------------------- -->
            <div class="col-lg-4 mt-2">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header">
                        <h2>Users</h2>
                    </div>
                    <div class="card-body">
                        @if ($users->isEmpty())
                            <p>No users found.</p>
                        @else
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <th scope="row">{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td>
                                                <a href="{{ route('client.users.profile', $user->id) }}" class="btn btn-primary btn-sm bi bi-binoculars"> View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- ------------------------------------------------- -->
        <!-- Profile action's -->
        <!-- ------------------------------------------------- -->
        <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
            <div class="col-lg-2 m-2">
                <div class="row">
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary bi bi-backspace"> Back to Clients</a>
                </div>
            </div>

            <!-- Edit-knapp -->
            <div class="col-lg-2 m-2">
                <div class="row">
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn bi bi-pencil"> Edit</a>
                </div>
            </div>

            <!-- Delete-knapp med bekreftelse -->
            <form class="col-lg-2 m-2" action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <div class="row">
                    <button type="submit" class="btn btn-danger btn bi bi-trash3" onclick="return confirm('Are you sure you want to delete this client?')">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endsection
