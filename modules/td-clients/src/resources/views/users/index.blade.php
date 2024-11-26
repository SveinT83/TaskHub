@extends('layouts.app')

@section('title', 'All Users')

@section('pageHeader')
    <h1>All Users</h1>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <!-- Sjekk om det finnes brukere -->
                    @if ($users->isEmpty())
                        <p>No users found.</p>
                    @else

                    @foreach ($users as $user)
                        <div class="row justify-content-between">
                            <div class="col-md-8">
                                <div class="row justify-content-start">
                                    <p class="col-lg-2 bi bi-person"> {{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p class="col-lg-3 bi bi-envelope"> {{ $user->email }}</p>
                                    <p class="col-lg-2 bi bi-telephone"> {{ $user->phone }}</p>
                                </div>
                            </div>
                                
                            <div class="col-lg-4">
                                <div class="row justify-content-evenly">
                                    <div class="col-3">
                                        <div class="row">
                                            <a href="{{ route('client.users.profile', $user->id) }}" class="btn btn-primary btn-sm bi bi-binoculars"> View</a>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <a href="{{ route('client.users.edit', $user->id) }}" class="btn btn-primary btn-sm bi bi-pencil"> Edit</a>
                                        </div>
                                    </div>
                                    <form class="col-3" action="{{ route('client.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <div class="row">
                                            <button type="submit" class="btn btn-danger btn-sm bi bi-trash3" onclick="return confirm('Are you sure?')"> Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @endif
                </div>
            </div>

            <!-- Legg til ny bruker -->
            <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
                <div class="col-md-2">
                    <div class="row">
                        <a href="{{ route('client.users.create') }}" class="btn btn-success">Add New User</a>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
