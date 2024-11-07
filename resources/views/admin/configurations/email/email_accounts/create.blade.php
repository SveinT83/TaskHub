@extends('layouts.app')

@section('pageHeader')
    <x-page-header pageHeaderTitle="Add email account"></x-page-header>
@endsection

@section('content')
    @livewire('admin.configurations.email.email-form')
@endsection
