@extends('layouts.app')

@section('title', 'Home')

@section('pageHeader')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <!-- ------------------------------------------------- -->
    <!-- Conatainer -->
    <!-- ------------------------------------------------- -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <x-card-primary title="Welcome to the Dashboard">
                    <p>
                        This is the dashboard page. You can manage your posts, categories, and tags here.
                    </p>
                </x-card-primary>
            </div>
        </div>
    </div>
@endsection