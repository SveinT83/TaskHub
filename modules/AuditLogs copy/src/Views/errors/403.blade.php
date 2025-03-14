@extends('core::layouts.app')

@section('content')
<h1>403 - Unauthorized</h1>
<p>You do not have permission to access this page.</p>
<a href="{{ route('home') }}">Return Home</a>
@endsection