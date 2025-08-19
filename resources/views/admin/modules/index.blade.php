@extends('layouts.app')

@section('title', 'Modules')

@section('pageHeader')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Modules</h1>
            <p class="text-muted mb-0">Manage installed modules and their status</p>
        </div>
    </div>
@endsection

@section('content')
    <livewire:admin.modules.module-manager />
@endsection