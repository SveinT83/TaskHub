@extends('layouts.app')

@section('pageHeader')
    <h1>Metadata</h1>
@endsection

@section('content')
    <h1>Metadata</h1>
    <livewire:admin.configurations.meta.index :model="$model" :id="$id" />
@endsection
