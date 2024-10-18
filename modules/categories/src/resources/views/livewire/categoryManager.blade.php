
    <!-- resources/views/livewire/category.blade.php -->
    <div>

        <!-- Kategorilisten -->
        <div class="card">

            <!-- ------------------------------------------------- -->
            <!-- Card Header -->
            <!-- ------------------------------------------------- -->
            <div class="card-header">
                <div class="row align-items-center">
                    <h3 class="col-10">Categories</h3>

                    <!-- ------------------------------------------------- -->
                    <!-- New Category Button -->
                    <!-- ------------------------------------------------- -->
                    <button class="col-2 btn btn-link bi bi-plus" wire:click="$toggle('showForm')"> Add category</button>
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
                    @include('categories::partials.new-category-form')

                @else

                    <!-- -------------------------------------------------------------------------------------------------- -->
                    <!-- Else show category list -->
                    <!-- -------------------------------------------------------------------------------------------------- -->
                        @foreach ($categories as $category)
                            <div class="row border mt-3">
                                @include('categories::partials.category-item', ['category' => $category])
                            </div>
                        @endforeach
                @endif

            </div>
        </div>
</div>
