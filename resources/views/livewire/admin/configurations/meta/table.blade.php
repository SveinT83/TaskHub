<div>
    <form wire:submit.prevent="save">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Meta Data Values</h5>
            </div>
            @if($fields->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Key</th>
                                <th>Label</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fields as $field)
                                <tr>
                                    <td><strong>{{ $field->key }}</strong></td>
                                    <td>{{ $field->label }}</td>
                                    <td>
                                        @if($field->type === 'select')
                                            <select wire:model.defer="values.{{ $field->key }}" class="form-select">
                                                <option value="">Select...</option>
                                                @foreach($field->options ?? [] as $optKey => $optLabel)
                                                    <option value="{{ $optKey }}">{{ $optLabel }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($field->type === 'boolean')
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="values.{{ $field->key }}" 
                                                       value="1" class="form-check-input">
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                        @else
                                            <input type="text" wire:model.defer="values.{{ $field->key }}" class="form-control">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Lagre
                    </button>
                </div>
            @else
                <div class="card-body text-center py-4">
                    <i class="bi bi-info-circle display-4 text-muted"></i>
                    <h6 class="mt-3 text-muted">No meta fields available</h6>
                    <p class="text-muted">Define meta fields first to add data here.</p>
                </div>
            @endif
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
