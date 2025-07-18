@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">{{ __('Translation Management') }}</h1>
                <div class="btn-group">
                    <a href="{{ route('admin.translations.stats') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar"></i> {{ __('Statistics') }}
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-language fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        {{ __('Supported Languages') }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ count(config('app.supported_locales', [])) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-left-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-globe fa-2x text-info"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        {{ __('Current Language') }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ config('app.supported_locales')[app()->getLocale()] ?? app()->getLocale() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-cogs fa-2x text-success"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        {{ __('Fallback Language') }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ config('app.supported_locales')[config('app.fallback_locale')] ?? config('app.fallback_locale') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Translation Editor -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Translation Editor') }}</h6>
                </div>
                <div class="card-body p-0">
                    <livewire:admin.configurations.langue.translation-editor />
                </div>
            </div>

            <!-- CLI Commands Help -->
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('CLI Commands') }}</h6>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#cliHelp">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="collapse" id="cliHelp">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Command') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Example') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>lang:sync {locale}</code></td>
                                        <td>{{ __('Stub missing keys for a locale with empty values') }}</td>
                                        <td><code>php artisan lang:sync no</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>lang:export {locale} --format=csv</code></td>
                                        <td>{{ __('Export key/value pairs for external CAT tools') }}</td>
                                        <td><code>php artisan lang:export no --format=csv</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>lang:import {file}</code></td>
                                        <td>{{ __('Import translations and write to correct files') }}</td>
                                        <td><code>php artisan lang:import translations.csv</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>lang:lint --locale=en</code></td>
                                        <td>{{ __('Fails CI if default locale has empty values or duplicates') }}</td>
                                        <td><code>php artisan lang:lint --locale=no</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
