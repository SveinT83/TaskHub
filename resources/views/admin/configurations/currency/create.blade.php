@extends('layouts.app')

@section('pageHeader')
    <x-page-header pageHeaderTitle="{{ __('core.currency.add_currency') }}">
        <div class="col-md-auto mt-1">
            <a href="{{ route('currency.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> {{ __('core.ui.back') }}
            </a>
        </div>
    </x-page-header>
@endsection

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('core.currency.add_currency') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('currency.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">{{ __('core.currency.code') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="code"
                                           name="code"
                                           value="{{ old('code') }}"
                                           placeholder="EUR, USD, NOK..."
                                           maxlength="3"
                                           style="text-transform: uppercase"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">{{ __('core.currency.code_help') }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="symbol" class="form-label">{{ __('core.currency.symbol') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('symbol') is-invalid @enderror"
                                           id="symbol"
                                           name="symbol"
                                           value="{{ old('symbol') }}"
                                           placeholder="€, $, kr..."
                                           maxlength="10"
                                           required>
                                    @error('symbol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('core.ui.name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('core.currency.name_placeholder') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exchange_rate" class="form-label">{{ __('core.currency.exchange_rate') }} <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('exchange_rate') is-invalid @enderror"
                                   id="exchange_rate"
                                   name="exchange_rate"
                                   value="{{ old('exchange_rate', '1.0') }}"
                                   step="0.000001"
                                   min="0.000001"
                                   required>
                            @error('exchange_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('core.currency.exchange_rate_help') }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('active') is-invalid @enderror"
                                       type="checkbox"
                                       id="active"
                                       name="active"
                                       value="1"
                                       {{ old('active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">
                                    {{ __('core.ui.active') }}
                                </label>
                                @error('active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('core.currency.active_help') }}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('currency.index') }}" class="btn btn-secondary me-2">
                                {{ __('core.ui.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> {{ __('core.ui.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-uppercase currency code
document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>

@endsection
