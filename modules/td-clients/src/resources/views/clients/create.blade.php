@extends('layouts.app')

@section('title', 'Home')

@section('pageHeader')
    <h1>Create Client</h1>
@endsection

@section('content')
    <div class="container-fluid mt-3">

        <div class="card">
            <form class="card-body" action="{{ route('clients.store') }}" method="POST">
                @csrf <!-- Viktig for CSRF-beskyttelse -->

                <div class="row">
                    
                    <div class="col-md-4">

                        <h3>Client details:</h3>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Company name" required>
                        </div>

                        <div class="mb-3">
                            <label for="main_email" class="form-label fw-bold">Email address</label>
                            <input type="email" class="form-control" id="main_email" name="main_email" placeholder="name@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="vat_number" class="form-label fw-bold">VAT Number:</label>
                            <input type="text" class="form-control" id="vat_number" name="vat_number" placeholder="12345678012">
                        </div>

                        <div class="mb-3">
                            <label for="account_number" class="form-label fw-bold">Account number:</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" placeholder="12345678">
                        </div>
                    </div>



                    <div class="col-md-4">

                        <h3>Main Site:</h3>

                        <div class="mb-3">
                            <label for="site_name" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" placeholder="Site name">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">Adress</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="street 1">
                        </div>

                        <div class="mb-3">
                            <label for="zip" class="form-label fw-bold">Zip code:</label>
                            <input type="number" class="form-control" id="zip" name="zip" placeholder="7730">
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label fw-bold">City:</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Steinkjer">
                        </div>

                        <div class="mb-3">
                            <label for="county" class="form-label fw-bold">County:</label>
                            <input type="text" class="form-control" id="county" name="county" placeholder="Trøndelag">
                        </div>

                        <div class="mb-3">
                            <label for="state" class="form-label fw-bold">State:</label>
                            <input type="text" class="form-control" id="state" name="state" placeholder="state">
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label fw-bold">Country:</label>
                            <input type="text" class="form-control" id="country" name="country" placeholder="Norge">
                        </div>
                    </div>



                    <div class="col-md-4">

                        <h3>Main User:</h3>

                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="first_name">
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-bold">Last name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="last_name">
                        </div>

                        <div class="mb-3">
                            <label for="user_email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="user_email">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Phone</label>
                            <input type="number" class="form-control" id="phone" name="phone" placeholder="phone">
                        </div>
                    </div>
                </div>

                <!-- Lagre-knapp -->
                <div class="row justify-content-start align-items-center text-bg-light p-2 mt-4">
                    <div class="col-md-2 me-2">
                        <div class="row">
                            <button type="submit" class="btn btn-primary"> Save</button>
                        </div>
                    </div>
                    <div class="col-md-2 me-2">
                        <div class="row">
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary bi bi-slash-circle"> Cansel</a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
