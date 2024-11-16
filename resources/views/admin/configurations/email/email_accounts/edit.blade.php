<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Configurations/EmailAccountController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Page Header -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Edit email account"></x-page-header>
@endsection

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Content -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('content')

    <!-- ------------------------------------------------- -->
    <!-- LIVEWIRE COMPONENT -->
    <!-- ------------------------------------------------- -->
    @livewire('admin.configurations.email.email-form', ['emailAccount' => $emailAccount])
@endsection