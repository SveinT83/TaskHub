@extends('layouts.app')

@section('title', 'Edit Site')

@section('pageHeader')
<h1>Edit Site: {{ old('name', $site->name) }}</h1>
@endsection

@section('content')
    <div class="container mt-3">
        <!-- Skjema for å redigere siten -->
        <form action="{{ route('client.sites.update', $site->id) }}" class="card" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Site Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $site->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $site->address) }}">
                </div>

                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $site->city) }}">
                </div>

                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $site->country) }}">
                </div>

                <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
                    <div class="col-md-2 m-1">
                        <div class="row">
                            <button type="submit" class="btn btn-primary  bi bi-save"> Save Changes</button>
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
