@extends('layouts.app')

@section('title', 'Create New Site')

@section('pageHeader')
    <h1>Create New Site</h1>
@endsection

@section('content')
    <div class="container mt-3">

        <!-- Skjema for å opprette ny site -->
        <form action="{{ route('client.sites.store') }}" class="card" method="POST">
            @csrf

            <div class="card-body">
                <!-- Sjekk om client_id er tilstede -->
                @if (isset($client_id))
                    <input type="hidden" name="client_id" value="{{ $client_id }}">
                    <p>Client: {{ $client->name }}</p> <!-- Viser klientens navn dersom client_id er satt -->
                @else
                    <div class="mb-3">
                        <label for="client_id" class="form-label fw-bold">Client</label>
                        <select class="form-select" id="client_id" name="client_id" required>
                            <option value="" disabled selected>Select a Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Site Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label fw-bold">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                </div>

                <div class="mb-3">
                    <label for="city" class="form-label fw-bold">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                </div>

                <div class="mb-3">
                    <label for="country" class="form-label fw-bold">Country</label>
                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}">
                </div>

                <div class="mb-3">
                    <label for="zip" class="form-label fw-bold">Zip Code</label>
                    <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}">
                </div>

                <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
                    <div class="col-md-2 m-1">
                        <div class="row">
                            <button type="submit" class="btn btn-success bi bi-save"> Create Site</button>
                        </div>
                    </div>
                    <div class="col-md-2 m-1">
                        <div class="row">
                            <a href="{{ route('client.sites.index') }}" class="btn btn-secondary bi bi-slash-circle"> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
