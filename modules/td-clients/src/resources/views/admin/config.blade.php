@extends('layouts.app')

@section('title', 'Client Module Configuration')

@section('pageHeader')
    <h1>Client Module Configuration</h1>
@endsection

@section('content')
    <div class="container mt-3">
        
        <div class="row">

            <!-- Clients Card -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Clients</h2>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Clients: {{ $clientCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Sites Card -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Sites</h2>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Sites: {{ $siteCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Users Card -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>Users</h2>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Total Users: {{ $userCount }}</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
