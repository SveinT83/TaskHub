<div class="card shadow">
    <!-- Header -->
    <div class="card-header border-bottom">
        <h5 class="card-title mb-0 font-weight-bold text-dark">{{ __('core.ui.settings') }} - {{ __('Translation Editor') }}</h5>
    </div>

    <!-- Controls -->
    <div class="card-body bg-light border-bottom">
        <div class="row">
            <!-- Locale Selector -->
            <div class="col-md-3">
                <label class="form-label small font-weight-bold text-dark">
                    {{ __('Language') }}
                </label>
                <select wire:model.live="currentLocale" class="form-control">
                    @foreach($availableLocales as $code => $name)
                        <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Namespace Selector -->
            <div class="col-md-3">
                <label class="form-label small font-weight-bold text-dark">
                    {{ __('Module') }}
                </label>
                <select wire:model.live="selectedNamespace" class="form-control">
                    @foreach($availableNamespaces as $namespace)
                        <option value="{{ $namespace }}">{{ ucfirst($namespace) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- File Selector -->
            <div class="col-md-3">
                <label class="form-label small font-weight-bold text-dark">
                    {{ __('File') }}
                </label>
                <select wire:model.live="selectedFile" class="form-control">
                    @foreach($availableFiles as $file)
                        <option value="{{ $file }}">{{ $file }}.php</option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div class="col-md-3">
                <label class="form-label small font-weight-bold text-dark">
                    {{ __('core.ui.search') }}
                </label>
                <div class="input-group">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchTerm" 
                        placeholder="{{ __('Search translations...') }}"
                        class="form-control"
                    >
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Translations Table -->
    <div class="card-body">
        @if(empty($this->filteredTranslations))
            <div class="text-center py-5 text-muted">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="font-weight-bold text-dark">{{ __('core::tables.no_data') }}</h5>
                <p class="text-muted">{{ __('No translations found for the selected criteria.') }}</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold text-uppercase small">
                                {{ __('Key') }}
                            </th>
                            <th class="font-weight-bold text-uppercase small">
                                {{ __('Translation') }}
                            </th>
                            <th class="text-right font-weight-bold text-uppercase small">
                                {{ __('core.ui.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->filteredTranslations as $key => $value)
                            <tr>
                                <td class="align-middle">
                                    <code class="bg-light px-2 py-1 rounded small">{{ $key }}</code>
                                </td>
                                <td class="align-middle">
                                    @if($editingKey === $key)
                                        <div>
                                            <textarea 
                                                wire:model="editingValue"
                                                rows="3"
                                                class="form-control @error('editingValue') is-invalid @enderror"
                                            ></textarea>
                                            @error('editingValue')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <button 
                                                    wire:click="saveTranslation"
                                                    class="btn btn-success btn-sm mr-2"
                                                >
                                                    <i class="fas fa-save"></i> {{ __('core.ui.save') }}
                                                </button>
                                                <button 
                                                    wire:click="cancelEdit"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    <i class="fas fa-times"></i> {{ __('core.ui.cancel') }}
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div style="max-width: 400px;">
                                            @if(empty($value))
                                                <span class="text-danger font-italic">{{ __('Empty translation') }}</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right align-middle">
                                    @if($editingKey !== $key)
                                        <button 
                                            wire:click="editTranslation('{{ $key }}')"
                                            class="btn btn-outline-primary btn-sm"
                                        >
                                            <i class="fas fa-edit"></i> {{ __('core.ui.edit') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="card-footer bg-light text-muted small">
        <div class="d-flex justify-content-between align-items-center">
            <span>
                {{ __('core.tables.showing', [
                    'from' => 1,
                    'to' => count($this->filteredTranslations),
                    'total' => count($this->filteredTranslations)
                ]) }}
            </span>
            <span>
                {{ __('Current locale') }}: <strong>{{ $availableLocales[$currentLocale] ?? $currentLocale }}</strong>
            </span>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('translation-saved', (event) => {
        // Optional: Show toast notification or refresh other components
        console.log('Translation saved:', event);
    });
</script>
@endscript
