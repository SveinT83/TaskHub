<form class="row" wire:submit.prevent="saveCategory">
    <div class="mb-3">
        <label for="newCategory" class="form-label fw-bold">Category Name:</label>
        <input type="text" class="form-control" id="newCategory" wire:model="newCategory">
        @error('newCategory') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label fw-bold">Description:</label>
        <textarea class="form-control" id="description" wire:model="description"></textarea>
        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="parentCategory" class="form-label fw-bold">Parent Category:</label>
        <select class="form-select" id="parentCategory" wire:model="parent_id">
            <option value="">No Parent</option>
            @foreach($categories as $category)
                @include('categories::partials.category-option', ['category' => $category, 'level' => 0])
            @endforeach
        </select>
        @error('parent_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    @if($isEditMode)
        <div class="mb-3">
            <label for="slug" class="form-label fw-bold">Slug:</label>
            <input type="text" class="form-control" id="slug" wire:model="slug">
            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fw-bold">Status:</label>
            <select class="form-select" id="status" wire:model="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endif

    <button type="submit" class="btn btn-success bi bi-floppy">
        {{ $isEditMode ? 'Update Category' : 'Add Category' }}
    </button>

    <!-- Abort Button -->
    <button type="button" class="btn btn-outline-secondary btn-sm mt-1 bi bi-x-lg" wire:click="$toggle('showForm')"> Cancel</button>
</form>
