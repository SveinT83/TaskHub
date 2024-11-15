@extends('layouts.app')

@section('title', 'Edit Client')

@section('pageHeader')
    <h1>Edit Client</h1>
@endsection

@section('content')
    <div class="container-fluid mt-3">

        <div class="card">
            <form class="card-body" action="{{ route('clients.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Angir PUT-metoden for oppdatering -->

                <div class="row">
                    
                    <div class="col-md-4">

                        <h3>Client details:</h3>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('first_name', $client->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="main_email" class="form-label fw-bold">Email address</label>
                            <input type="email" class="form-control" id="main_email" name="main_email" value="{{ old('first_name', $client->main_email ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="vat_number" class="form-label fw-bold">VAT Number:</label>
                            <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ old('first_name', $client->vat_number ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="account_number" class="form-label fw-bold">Account number:</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('first_name', $client->account_number ?? '') }}">
                        </div>
                    </div>

                    <div class="col-md-4">

                        <h3>Main Site:</h3>

                        @if($mainSite)
                        <div class="mb-3">
                            <label for="site_name" class="form-label fw-bold">Site Name</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('first_name', $mainSite->name ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('first_name', $mainSite->address ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="zip" class="form-label fw-bold">Zip Code</label>
                            <input type="number" class="form-control" id="zip" name="zip" value="{{ old('first_name', $mainSite->zip ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label fw-bold">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('first_name', $mainSite->city ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="county" class="form-label fw-bold">County</label>
                            <input type="text" class="form-control" id="county" name="county" value="{{ old('first_name', $mainSite->county ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="state" class="form-label fw-bold">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('first_name', $mainSite->state ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label fw-bold">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ old('first_name', $mainSite->country ?? '') }}">
                        </div>
                        @else
                            <p>No site available.</p>
                        @endif
                    </div>

                    <div class="col-md-4">

                        <h3>Main User:</h3>

                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $mainUser->first_name ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('first_name', $mainUser->last_name ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="user_email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" value="{{ old('first_name', $mainUser->email ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Phone</label>
                            <input type="number" class="form-control" id="phone" name="phone" value="{{ old('first_name', $mainUser->phone ?? '') }}">
                        </div>
                    </div>
                </div>

                <!-- Lagre-knapp -->
                <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
                    <div class="col-md-2 me-2">
                        <div class="row">
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary bi bi-backspace"> Cancel</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <button type="submit" class="btn btn-primary bi bi-save"> Save Changes</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
