<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Extend the layout -->
<!-- -------------------------------------------------------------------------------------------------- -->
@extends('layouts.app')

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Title -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('title', 'Tasks Configuration')

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Page Header -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('pageHeader')
    <h1>Task Modulen - Konfigurasjon</h1>
@endsection

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Content -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('content')
<div class="container mt-4">

    <!-- ------------------------------------------------- -->
    <!-- FORM START -->
    <!-- ------------------------------------------------- -->
    <form method="POST" action="{{ route('tasks.config.save') }}">
        @csrf
        <div class="mb-3">
            <label for="nextcloud_sync" class="form-label">Synkronisering med Nextcloud</label>
            <select id="nextcloud_sync" name="nextcloud_sync" class="form-control">
                <option value="1" {{ config('tasks.nextcloud_sync') == 1 ? 'selected' : '' }}>Aktivert</option>
                <option value="0" {{ config('tasks.nextcloud_sync') == 0 ? 'selected' : '' }}>Deaktivert</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lagre innstillinger</button>
    </form>
    <!-- ------------------------------------------------- -->
    <!-- FORM END -->
    <!-- ------------------------------------------------- -->

</div>
@endsection
