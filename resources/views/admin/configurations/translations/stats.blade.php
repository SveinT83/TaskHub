@extends('layouts.app')

@section('pageHeader')
    <div class="row">
        <div class="col-11">
            <h1>{{ __('Translation Statistics') }}</h1>
        </div>
        <div class="col-1 text-right">
            <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> {{ __('core::ui.back') }}
                </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                @foreach($stats as $locale => $data)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card border-left-{{ $data['stats']['percentage'] >= 90 ? 'success' : ($data['stats']['percentage'] >= 50 ? 'warning' : 'danger') }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">
                                        @switch($locale)
                                            @case('no')
                                                🇳🇴
                                                @break
                                            @case('da')
                                                🇩🇰
                                                @break
                                            @case('sv')
                                                🇸🇪
                                                @break
                                            @default
                                                🇬🇧
                                        @endswitch
                                        {{ $data['name'] }}
                                    </h5>
                                    <span class="badge badge-{{ $data['stats']['percentage'] >= 90 ? 'success' : ($data['stats']['percentage'] >= 50 ? 'warning' : 'danger') }}">
                                        {{ $data['stats']['percentage'] }}%
                                    </span>
                                </div>

                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $data['stats']['percentage'] >= 90 ? 'success' : ($data['stats']['percentage'] >= 50 ? 'warning' : 'danger') }}" 
                                         role="progressbar" 
                                         style="width: {{ $data['stats']['percentage'] }}%">
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            {{ __('Translated') }}
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $data['stats']['translated'] }}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            {{ __('Missing') }}
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $data['stats']['missing'] }}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            {{ __('Total') }}
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $data['stats']['total'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Overall Progress -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Overall Translation Progress') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Language') }}</th>
                                    <th>{{ __('Progress') }}</th>
                                    <th>{{ __('Translated') }}</th>
                                    <th>{{ __('Missing') }}</th>
                                    <th>{{ __('Total') }}</th>
                                    <th>{{ __('Percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats as $locale => $data)
                                    <tr>
                                        <td>
                                            @switch($locale)
                                                @case('no')
                                                    🇳🇴
                                                    @break
                                                @case('da')
                                                    🇩🇰
                                                    @break
                                                @case('sv')
                                                    🇸🇪
                                                    @break
                                                @default
                                                    🇬🇧
                                            @endswitch
                                            {{ $data['name'] }}
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-{{ $data['stats']['percentage'] >= 90 ? 'success' : ($data['stats']['percentage'] >= 50 ? 'warning' : 'danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $data['stats']['percentage'] }}%">
                                                    {{ $data['stats']['percentage'] }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $data['stats']['translated'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">{{ $data['stats']['missing'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $data['stats']['total'] }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $data['stats']['percentage'] }}%</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection