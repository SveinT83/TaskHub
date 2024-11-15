@extends('layouts.app')

@section('title', 'Create New User')

@section('pageHeader')
    <h1>Create New User</h1>
@endsection

@section('content')
    <div class="container-fluid mt-3">

        <!-- ------------------------------------------------- -->
        <!-- New User Form -->
        <!-- ------------------------------------------------- -->
        <form class="card" action="{{ route('client.users.store') }}" method="POST">
            @csrf <!-- Viktig for CSRF-beskyttelse -->
            <div class="card-body">
                <div class="row">

                    <!-- ------------------------------------------------- -->
                    <!-- Left - User Details -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Right - Client and site details -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="client_id" class="form-label fw-bold">Client</label>
                            <select class="form-select" id="client_id" name="client_id" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="site_id" class="form-label fw-bold">Site</label>
                            <select class="form-select" id="site_id" name="site_id">
                                <option value="">Select Site</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ------------------------------------------------- -->
                <!-- Acton buttons -->
                <!-- ------------------------------------------------- -->
                <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
                    <div class="col-md-2 me-2">
                        <div class="row">
                            <button type="submit" class="btn btn-success"> Save</button>
                        </div>
                    </div>
                    <div class="col-md-2 me-2">
                        <div class="row">
                            <a href="{{ route('client.users.index') }}" class="btn btn-secondary  bi bi-slash-circle"> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ------------------------------------------------- -->
    <!-- Script's -->
    <!-- ------------------------------------------------- -->
    <script>

        //--------------------------------------------------------------------------------------------------
        // Fetch sites for selected client
        //------------------------------------------------- -------------------------------------------------
        document.getElementById('client_id').addEventListener('change', function() {

            //-------------------------------------------------
            // Variables
            //-------------------------------------------------
            var clientId = this.value;
            var siteSelect = document.getElementById('site_id');
            
            //-------------------------------------------------
            // If client selected
            //-------------------------------------------------
            if (clientId) {
                fetch(`/api/clients/${clientId}/sites`)
                    .then(response => response.json())
                    .then(data => {

                        //-------------------------------------------------
                        // Reset sites
                        //-------------------------------------------------
                        siteSelect.innerHTML = '<option value="">Select Site</option>';

                        //-------------------------------------------------
                        // Add sites to select
                        //-------------------------------------------------
                        data.forEach(site => {
                            var option = document.createElement('option');
                            option.value = site.id;
                            option.textContent = site.name;
                            siteSelect.appendChild(option);
                        });
                    });

            //-------------------------------------------------
            // If no client selected
            //-------------------------------------------------
            } else {

                //-------------------------------------------------
                // Clear site select if no client selected
                //-------------------------------------------------
                siteSelect.innerHTML = '<option value="">Select Site</option>';
            }
        });
    </script>
@endsection
