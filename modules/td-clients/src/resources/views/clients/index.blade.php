@extends('layouts.app')

@section('title', 'Home')

@section('pageHeader')
    <h1>Clients</h1>
@endsection

@section('content')
    <div class="container-fluid mt-3">

        <div class="row">
            <div class="card">
                <ul class="list-group list-group-flush">
                    @if ($clients->isEmpty())
                    <li class="list-group-item">Ingen klienter å vise.</li>
                    @else
                        <ul  class="list-group list-group-flush">
                            @foreach ($clients as $client)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="bi bi-building"> {{ $client->name }}</span>

                                    <div>
                                        <!-- View-knapp -->
                                        <a href="{{ route('clients.profile', $client->id) }}" class="btn btn-primary btn-sm me-2 bi bi-binoculars"> View</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </ul>
            </div>
        </div>

        <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
            <div class="col-md-2">
                <div class="row">
                    <a href="{{ route('clients.create') }}" class="btn btn-primary bi bi-plus"> Create New Client</a>
                </div>
            </div>
        </div>
    </div>
@endsection
