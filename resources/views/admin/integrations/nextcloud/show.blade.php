@extends('layouts.app')

@section('title', 'Home')

@section('pageHeader')
    <h1>Nextcloud Integrasjon</h1>
@endsection

@section('content')

<!-- ------------------------------------------------- -->
<!-- Container -->
<!-- ------------------------------------------------- -->
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Alerts -->
    <!-- ------------------------------------------------- -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- ------------------------------------------------- -->
    <!-- Nextcloud connect -->
    <!-- ------------------------------------------------- -->
    <div class="row">
        @if($user && $user->nextcloud_token)
            <p>Nextcloud er tilkoblet.</p>
        @else
            <p>Nextcloud er ikke tilkoblet.</p>
            <a href="{{ route('nextcloud.connect') }}" class="btn btn-primary">Koble til Nextcloud</a>
        @endif
    </div>

    <!-- ------------------------------------------------- -->
    <!-- Kontroll for å aktivere eller deaktivere Nextcloud-integrasjon -->
    <!-- ------------------------------------------------- -->
    <form action="{{ route('nextcloud.toggle') }}" method="POST">
        @csrf
        @if($isNextcloudActive)
            <button type="submit" class="btn btn-danger">Deaktiver Nextcloud</button>
        @else
            <button type="submit" class="btn btn-success">Aktiver Nextcloud</button>
        @endif
    </form>
</div>
@endsection
