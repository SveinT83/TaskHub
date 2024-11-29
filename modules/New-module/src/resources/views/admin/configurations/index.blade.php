<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Configurations/ConfigurationsController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->
@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Configurations"></x-page-header>
@endsection

@section('content')
    <!-- ------------------------------------------------- -->
    <!-- Card -->
    <!-- ------------------------------------------------- -->
    <x-card-secondary title="Main Configurations">
        <p>Here you can configure the main settings for the application.</p>
    </x-card-secondary>
@endsection
