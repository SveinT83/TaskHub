@extends('layouts.guest')  <!-- Utvider guest.blade.php layouten -->

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        
        <div class="card mt-3">
            <div class="card-header">
                <h1>Logg inn med Nextcloud</h1>
            </div>

            <div class="card-body">

                <!-- Tilknytt Nextcloud-knappen -->
                <div class="row">
                    <a href="{{ route('login.nextcloud') }}" class="btn btn-primary">
                        Logg inn
                    </a>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('login') }}">Logg inn med e-post</a>
            </div>
        </div>
    </div>
</div>
@endsection