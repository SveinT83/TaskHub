@extends('layouts.app')

@section('pageHeader')
    <h1>{{ $wall->name }}</h1>
@endsection

@section('content')
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between align-items-center">
                        <h2 class="col-10">Wall Details</h2>
                        <div class="col-2 text-end">
                            <a href="" class="btn btn-outline-primary btn-sm">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <p class="col-md-6">{{ $wall->description }}</p>
                        <p class="col-md-6">Stats about tasks comes here...</p>
                    </div>

                    <div class="row mt-2">
                        <p class="col-lg-2" style="font-size: 10px;"><strong> Created:</strong> {{ $wall->created_at }}</p>
                        <p class="col-lg-2" style="font-size: 10px;"><strong>Updated:</strong> {{ $wall->updated_at }}</p>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('tasks.create', ['wall_id' => $wall->id]) }}" class="btn btn-primary bi bi-plus"> Add task</a>
                </div>
            </div>
        </div>
    </div>

@endsection
