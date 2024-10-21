@extends('layouts.app')

@section('pageHeader')
    <h2>Kb Articles</h2>
@endsection

@section('content')
    <livewire:article-form :articleId="$articleId" />
@endsection