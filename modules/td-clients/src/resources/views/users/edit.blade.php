@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="container mt-5">
        <h1>Edit User</h1>

        <!-- Skjema for å redigere brukeren -->
        <form action="{{ route('client.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>

            <!-- Dropdown for å flytte brukeren til en annen site -->
            <div class="mb-3">
                <label for="site_id" class="form-label">Site</label>
                <select class="form-select" id="site_id" name="site_id" required>
                    <option value="" disabled>Select a Site</option>
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}" {{ $user->site_id == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->address }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('client.users.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
