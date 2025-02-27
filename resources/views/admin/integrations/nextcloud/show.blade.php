<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Integrations/Nextcloud/NextcloudController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

@section('title', 'Home')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Nextcloud Integrasjon"></x-page-header>
@endsection

@section('content')

<!-- ------------------------------------------------- -->
<!-- Container -->
<!-- ------------------------------------------------- -->
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Card -->
    <!-- ------------------------------------------------- -->
    <x-card-secondary title="Connect / Dissconnect">

        <!-- ------------------------------------------------- -->
        <!-- Nextcloud connect -->
        <!-- ------------------------------------------------- -->
        <div class="row">
            @if($user && $user->nextcloud_token)
                <p>Nextcloud is connected.</p>
            @else
                <p>Nextcloud is not connected.</p>

                <i class="mt-2">Put your Nextcloud details in the .env file.</i>
            @endif
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Kontroll for å aktivere eller deaktivere Nextcloud-integrasjon -->
        <!-- ------------------------------------------------- -->
        <form class="row mt-3" action="{{ route('nextcloud.toggle') }}" method="POST">
            @csrf
            @if($isNextcloudActive)
                <button type="submit" class="btn btn-danger">Deconnect Nextcloud</button>
            @else
                <button type="submit" class="btn btn-success">Connect Nextcloud</button>
            @endif
        </form>

    </x-card-secondary>
</div>
@endsection
