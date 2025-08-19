@extends('layouts.app')

@section('pageHeader')
    <h1>Meta Field Definitions</h1>
    <p class="text-gray-600 mb-6">Manage custom field definitions that can be used across all entities</p>
@endsection

@section('content')
    <livewire:admin.configurations.meta.field-manager />
@endsection
