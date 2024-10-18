<div>

    <!-- ------------------------------------------------- -->
    <!-- Form Card START -->
    <!-- ------------------------------------------------- -->
    <div class="card mt-3">

        <!-- ------------------------------------------------- -->
        <!-- Form and card body -->
        <!-- ------------------------------------------------- -->
        <form class="card-body" wire:submit.prevent="save">

            <!-- ------------------------------------------------- -->
            <!-- Title -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="title" class="form-label fw-bold">Title</label>
                <input type="text" class="form-control" wire:model="title" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Content -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="content" class="form-label fw-bold">Content</label>
                <textarea class="form-control" rows="5" wire:model="content"></textarea>
                @error('content') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Category -->
            <!-- ------------------------------------------------- -->
            @if(!empty($categories))
                <div class="mb-3">
                    <label for="category" class="form-label fw-bold">Category</label>
                    <select class="form-control" wire:model="category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            @include('kbartickles::partials.category-option', ['category' => $category, 'level' => 0])
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Status -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="status" class="form-label fw-bold">Status</label>
                <select class="form-control" wire:model="status">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Buttons -->
            <!-- ------------------------------------------------- -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('kb.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
    <!-- ------------------------------------------------- -->
    <!-- Form Card END -->
    <!-- ------------------------------------------------- -->

</div>
