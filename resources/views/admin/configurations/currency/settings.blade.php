@extends('layouts.app')

@section('pageHeader')
    <x-page-header pageHeaderTitle="{{ __('core.currency.settings') }}">
        <div class="col-md-auto mt-1">
            <a href="{{ route('currency.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> {{ __('core.ui.back') }}
            </a>
        </div>
    </x-page-header>
@endsection

@section('content')
<div class="container mt-3">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('core.currency.exchange_rate_settings') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.configurations.currency.settings.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="exchange_rate_provider" class="form-label">{{ __('core.currency.api_provider') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('exchange_rate_provider') is-invalid @enderror"
                                    id="exchange_rate_provider"
                                    name="exchange_rate_provider"
                                    required>
                                <option value="">{{ __('core.ui.select_option') }}</option>
                                <option value="exchangerate-api"
                                        {{ old('exchange_rate_provider', $settings['exchange_rate_provider']?->value) === 'exchangerate-api' ? 'selected' : '' }}>
                                    ExchangeRate-API (exchangerate-api.com)
                                </option>
                                <option value="openexchangerates"
                                        {{ old('exchange_rate_provider', $settings['exchange_rate_provider']?->value) === 'openexchangerates' ? 'selected' : '' }}>
                                    Open Exchange Rates (openexchangerates.org)
                                </option>
                            </select>
                            @error('exchange_rate_provider')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exchange_rate_api_key" class="form-label">{{ __('core.currency.api_key') }}</label>
                            <input type="text"
                                   class="form-control @error('exchange_rate_api_key') is-invalid @enderror"
                                   id="exchange_rate_api_key"
                                   name="exchange_rate_api_key"
                                   value="{{ old('exchange_rate_api_key', $settings['exchange_rate_api_key']?->value) }}"
                                   placeholder="{{ __('core.currency.api_key_placeholder') }}">
                            @error('exchange_rate_api_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('core.currency.api_key_help') }}</div>
                        </div>                        <!-- API Provider Information -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title">{{ __('core.currency.api_provider_info') }}</h6>

                                <div id="exchangerate-api-info" class="provider-info" style="display: none;">
                                    <p><strong>ExchangeRate-API:</strong></p>
                                    <ul class="small">
                                        <li>{{ __('core.currency.exchangerate_api_free') }}</li>
                                        <li>{{ __('core.currency.exchangerate_api_features') }}</li>
                                        <li>{{ __('core.currency.exchangerate_api_signup') }}: <a href="https://www.exchangerate-api.com/" target="_blank">exchangerate-api.com</a></li>
                                    </ul>
                                </div>                                                                <div id="openexchangerates-info" class="provider-info" style="display: none;">
                                    <p><strong>Open Exchange Rates:</strong></p>
                                    <ul class="small">
                                        <li>{{ __('core.currency.openexchangerates_free') }}</li>
                                        <li>{{ __('core.currency.openexchangerates_features') }}</li>
                                    </ul>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('currency.index') }}" class="btn btn-secondary me-2">
                                {{ __('core::ui.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> {{ __('core::ui.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const providerSelect = document.getElementById('exchange_rate_provider');
    const providerInfos = document.querySelectorAll('.provider-info');

    function showProviderInfo() {
        // Hide all provider info
        providerInfos.forEach(info => info.style.display = 'none');

        // Show selected provider info
        const selected = providerSelect.value;
        if (selected) {
            const info = document.getElementById(selected + '-info');
            if (info) {
                info.style.display = 'block';
            }
        }
    }

    providerSelect.addEventListener('change', showProviderInfo);

    // Show initial provider info
    showProviderInfo();
});
</script>

@endsection
