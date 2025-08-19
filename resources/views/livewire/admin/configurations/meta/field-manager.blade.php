<div>
    <div class="mb-3">
        <button wire:click="create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Field
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Fields Table -->
    <div class="card">
        @if($fields->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Key</th>
                            <th>Label</th>
                            <th>Type</th>
                            <th>Module</th>
                            <th>Required</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fields as $field)
                            <tr>
                                <td><strong>{{ $field->key }}</strong></td>
                                <td>{{ $field->label }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $field->type }}</span>
                                </td>
                                <td>{{ $field->module }}</td>
                                <td>
                                    @if(str_contains($field->rules ?? '', 'required'))
                                        <span class="text-danger"><strong>Yes</strong></span>
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="edit('{{ $field->key }}')" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button onclick="if(confirm('Are you sure you want to delete this field?')) { @this.call('delete', '{{ $field->key }}') }" 
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No meta fields defined</h5>
                <p class="text-muted">Get started by creating your first meta field definition.</p>
                <button wire:click="create" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Add New Field
                </button>
            </div>
        @endif
    </div>

    <!-- Modal for Create/Edit Field -->
    @if($showForm)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingField ? 'Edit' : 'Create' }} Meta Field
                        </h5>
                        <button type="button" wire:click="$set('showForm', false)" class="btn-close"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Key</label>
                                    <input type="text" wire:model.defer="form.key" 
                                           class="form-control"
                                           placeholder="e.g., preferred_language">
                                    @error('form.key') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Label</label>
                                    <input type="text" wire:model.defer="form.label" 
                                           class="form-control"
                                           placeholder="e.g., Preferred Language">
                                    @error('form.label') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <input type="text" wire:model.defer="form.description" 
                                           class="form-control"
                                           placeholder="Brief description of this field">
                                    @error('form.description') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Type</label>
                                    <select wire:model.defer="form.type" class="form-select">
                                        <option value="string">String</option>
                                        <option value="int">Integer</option>
                                        <option value="boolean">Boolean</option>
                                        <option value="json">JSON</option>
                                        <option value="select">Select</option>
                                    </select>
                                    @error('form.type') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Module</label>
                                    <input type="text" wire:model.defer="form.module" 
                                           class="form-control"
                                           placeholder="e.g., core, td-clients">
                                    @error('form.module') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Rules</label>
                                    <input type="text" wire:model.defer="form.rules" 
                                           class="form-control"
                                           placeholder="e.g., required|string|max:255">
                                    @error('form.rules') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Default Value</label>
                                    <input type="text" wire:model.defer="form.default_value" 
                                           class="form-control"
                                           placeholder="Default value (JSON format)">
                                    @error('form.default_value') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                @if($form['type'] === 'select')
                                    <div class="col-12">
                                        <label class="form-label">Options (JSON)</label>
                                        <textarea wire:model.defer="form.options" 
                                                  class="form-control" 
                                                  rows="3"
                                                  placeholder='{"option1": "Label 1", "option2": "Label 2"}'></textarea>
                                        @error('form.options') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="$set('showForm', false)" class="btn btn-secondary">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ $editingField ? 'Update' : 'Create' }} Field
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
