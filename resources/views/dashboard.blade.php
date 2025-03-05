@extends('layouts.app')

@section('title', 'Home')

@section('pageHeader')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <!-- ------------------------------------------------- -->
    <!-- Conatainer -->
    <!-- ------------------------------------------------- -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <!-- Viser widgets -->
                 @foreach($widgets as $widgetPosition)
                    @include($widgetPosition->widget->view_path, $widgetData[$widgetPosition->widget->id])
                @endforeach
            </div>
        </div>
    </div>
@endsection