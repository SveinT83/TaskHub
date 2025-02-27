@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="text-danger">403 - Ingen tilgang</h1>
    <p>Du har ikke tillatelse til å utføre denne handlingen.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Tilbake</a>
</div>
@endsection
