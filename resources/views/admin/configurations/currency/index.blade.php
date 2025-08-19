@extends('layouts.app')

@section('pageHeader')
    <x-page-header pageHeaderTitle="{{ __('core.currency.title') }}">
        <div class="col-md-auto mt-1">
            @can('currency.create')
                <x-new-url href="{{ route('currency.create') }}">{{ __('core.currency.add_currency') }}</x-new-url>
            @endcan

            @can('currency.edit')
                <button type="button" class="btn btn-outline-primary btn-sm ms-2" onclick="updateRates()">
                    <i class="bi bi-arrow-clockwise"></i> {{ __('core.currency.update_rates') }}
                </button>

                <a href="{{ route('admin.configurations.currency.settings') }}" class="btn btn-outline-secondary btn-sm ms-2">
                    <i class="bi bi-gear"></i> {{ __('core.ui.settings') }}
                </a>
            @endcan
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Currency Management Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('core.currency.manage_currencies') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('core.currency.code') }}</th>
                            <th>{{ __('core.ui.name') }}</th>
                            <th>{{ __('core.currency.symbol') }}</th>
                            <th>{{ __('core.currency.exchange_rate') }}</th>
                            <th>{{ __('core.currency.rate_updated_at') }}</th>
                            <th>{{ __('core.ui.active') }}</th>
                            <th>{{ __('core.ui.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currencies as $currency)
                            <tr class="{{ $defaultCurrency && $currency->code === $defaultCurrency->code ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $currency->code }}</strong>
                                    @if($defaultCurrency && $currency->code === $defaultCurrency->code)
                                        <span class="badge bg-primary ms-2">{{ __('core.currency.default') }}</span>
                                    @endif
                                </td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->symbol }}</td>
                                <td>{{ number_format($currency->exchange_rate, 6) }}</td>
                                <td>
                                    @if($currency->rate_updated_at)
                                        {{ $currency->rate_updated_at->format('Y-m-d H:i') }}
                                    @else
                                        <span class="text-muted">{{ __('core.ui.never') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($currency->active)
                                        <span class="badge bg-success">{{ __('core.ui.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('core.ui.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('currency.edit')
                                            <a href="{{ route('currency.edit', $currency) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            @if($currency->active && (!$defaultCurrency || $currency->code !== $defaultCurrency->code))
                                                <form action="{{ route('admin.configurations.currency.set-default', $currency) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ __('core.currency.set_default') }}">
                                                        <i class="bi bi-star"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('currency.delete')
                                            @if(!$defaultCurrency || $currency->code !== $defaultCurrency->code)
                                                <form action="{{ route('currency.destroy', $currency) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('core.ui.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">{{ __('core.currency.no_currencies_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($currencies->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $currencies->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateRates() {
    if (confirm('{{ __('core.currency.confirm_update_rates') }}')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.configurations.currency.update-rates') }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection
