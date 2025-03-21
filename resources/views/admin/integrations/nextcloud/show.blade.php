<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Integrations/Nextcloud/NextcloudController.php -->
<!-- filepath: /var/Projects/TaskHub/Dev/resources/views/admin/integrations/nextcloud/show.blade.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

<!-- ------------------------------------------------- -->
<!-- Show Nextcloud Integration -->
<!-- ------------------------------------------------- -->
@extends('layouts.app')
@section('title', 'Home')

<!-- ------------------------------------------------- -->
<!-- Include Header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Nextcloud Integration"></x-page-header>
@endsection

<!-- ------------------------------------------------- -->
<!-- Content Section -->
<!-- ------------------------------------------------- -->
@section('content')
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Card Start -->
    <!-- ------------------------------------------------- -->
    <x-card-secondary title="Connect / Disconnect">

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Header row, shows status of Nextcloud -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <div class="row">

            <!-- ------------------------------------------------- -->
            <!-- Show status of Nextcloud -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6">

                <!-- If Nextcloud credentials are set -->
                @if($user && $user->nextcloud_token)
                    <p>Nextcloud is activated.</p>
                @else
                    <p>Nextcloud is not activated.</p>
                @endif
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Toggle Nextcloud -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6">
                @if(isset($credentials->baseurl) && isset($credentials->clientid) && isset($credentials->clientsecret) && isset($credentials->redirecturi))
                    <form class="" action="{{ route('nextcloud.toggle') }}" method="POST">
                        @csrf
                        @if($isNextcloudActive)
                            <button type="submit" class="btn btn-danger">Activate Nextcloud</button>
                        @else
                            <button type="submit" class="btn btn-success">De activate Nextcloud</button>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Form to update Nextcloud credentials -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <form class="row mt-3" action="{{ route('nextcloud.updateCredentials') }}" method="POST">
            @csrf

            <!-- ------------------------------------------------- -->
            <!-- Base URL -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="baseurl">Base URL</label>
                <input type="text" class="form-control" id="baseurl" name="baseurl" value="{{ $credentials->baseurl ?? '' }}" placeholder="Enter Base URL" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Client ID -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="clientid">Client ID</label>
                <input type="text" class="form-control" id="clientid" name="clientid" value="{{ $credentials->clientid ?? '' }}" placeholder="Enter Client ID" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Client Secret -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="clientsecret">Client Secret</label>
                <input type="password" class="form-control" id="clientsecret" name="clientsecret" value="{{ $credentials->clientsecret ?? '' }}" placeholder="Enter Client Secret" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Redirect URI -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="redirecturi">Redirect URI</label>
                <input type="text" class="form-control" id="redirecturi" name="redirecturi" value="{{ $credentials->redirecturi ?? '' }}" placeholder="Enter Redirect URI" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Save/Update Button -->
            <!-- ------------------------------------------------- -->
            <button type="submit" class="btn btn-primary">Save/Update</button>
        </form>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- End of Card and container and Section -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    </x-card-secondary>
</div>
@endsection