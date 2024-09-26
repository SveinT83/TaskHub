@extends('layouts.app')

@section('content')
    <h1>{{ $wall->name }}</h1>
    <p>{{ $wall->description }}</p>
@endsection
