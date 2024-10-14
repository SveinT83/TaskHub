
    <!-- resources/views/livewire/category.blade.php -->
    <div>

        <!-- Kategorilisten -->
        <div class="card">

            <!-- ------------------------------------------------- -->
            <!-- Card Header -->
            <!-- ------------------------------------------------- -->
            <div class="card-header">
                <div class="row align-items-center">
                    <h3 class="col-11">Categories</h3>

                    <!-- ------------------------------------------------- -->
                    <!-- New Category Button -->
                    <!-- ------------------------------------------------- -->
                    <button class="col-1 btn btn-link" wire:click="$toggle('showForm')"><h3 class="bi bi-plus"></h3> </button>
                </div>
            </div>
            <div class="card-body">

                <!-- -------------------------------------------------------------------------------------------------- -->
                <!-- If new catygory form is shown -->
                <!-- -------------------------------------------------------------------------------------------------- -->
                @if($showForm)

                    <!-- ------------------------------------------------- -->
                    <!-- New category form -->
                    <!-- ------------------------------------------------- -->
                    <form class="row" wire:submit.prevent="addCategory">
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
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Add Category</button>
                    </form>

                @else

                    <!-- -------------------------------------------------------------------------------------------------- -->
                    <!-- Else show category list -->
                    <!-- -------------------------------------------------------------------------------------------------- -->
                    @foreach($categories as $category)

                        <!-- ------------------------------------------------- -->
                        <!-- Category Row -->
                        <!-- ------------------------------------------------- -->
                        <div class="row mt-1 border">
                            <div class="col">
                                {{ $category->name }}
                            </div>
                            <div class="col">
                                <button wire:click="deleteCategory({{ $category->id }})">Delete</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
</div>
