@extends('layouts.app')

@section('title', 'All Sites')

@section('pageHeader')
    <h1>All Sites</h1>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    @if ($sites->isEmpty())
                        <p class="list-group-item">No sites found.</p>
                    @else
                            @foreach ($sites as $site)
                            <div class="row justify-content-between">
                                <div class="col-md-8">
                                    <div class="row justify-content-start">
                                        <p class="col-md-4 col-lg-2 bi bi-geo-alt"> {{ $site->name }}</p>
                                        <p class="col-md-4 col-lg-2 bi bi-building"> {{ $site->client->name }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="row justify-content-evenly">
                                        <div class="col-3">
                                            <div class="row">
                                                <a href="{{ route('client.sites.profile', $site->id) }}" class="btn btn-primary btn-sm bi bi-binoculars"> View</a>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="row">
                                                <a href="{{ route('client.sites.edit', $site->id) }}" class="btn btn-primary btn-sm bi bi-pencil"> Edit</a>
                                            </div>
                                        </div>
                                        <form class="col-3" action="{{ route('client.sites.destroy', $site->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <div class="row">
                                                <button type="submit" class="btn btn-danger btn-sm bi bi-trash3" onclick="return confirm('Are you sure?')"> Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Legg til ny site -->
        <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
            <div class="col-md-2">
                <div class="row">
                    <a href="{{ route('client.sites.create') }}" class="btn btn-success">Add New Site</a>
                </div>
            </div>
        </div>
    </div>
@endsection
