@extends('layouts.app')

@section('pageHeader')
    <h1>Article Details</h1>
@endsection

@section('content')
    <livewire:article-view :articleId="$articleId" />
@endsection
