@extends('layouts.app')

@section('pageHeader')
    <h1>Tasks</h1>
@endsection

@section('content')

    <div class="row">

            <!-- ------------------------------------------------- -->
            <!-- A card whit all walls -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6 mt-3">
                @include('tdtask::partials.all_walls_card')
            </div>

            <!-- ------------------------------------------------- -->
            <!-- A card whit all tasks -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6 mt-3">
                @include('tdtask::partials.all_tasks_card')
            </div>

            <!-- ------------------------------------------------- -->
            <!-- A card whit all groups -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6 mt-3">
                @include('tdtask::partials.all_groups_card')
            </div>

    </div>

@endsection
