@extends('layouts.app')

@section('pageHeader')
    <x-page-header pageHeaderTitle="Edit email account"></x-page-header>
@endsection

@section('content')
    @livewire('admin.configurations.email.email-form', ['emailAccount' => $emailAccount])
@endsection
