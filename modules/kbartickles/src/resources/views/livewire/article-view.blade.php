<!-- ------------------------------------------------- -->
<!-- Card START -->
<!-- ------------------------------------------------- -->
<div class="card">

    <!-- ------------------------------------------------- -->
    <!-- Card Header -->
    <!-- ------------------------------------------------- -->
    <div class="card-header text-bg-primary">
        <div class="row justify-content-between">

            <!-- ------------------------------------------------- -->
            <!-- Card Title and Slug -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-10">
                <div class="row align-items-center">
                    <h2 class="col-auto">{{ $article->title }}</h2>
                    <p class="col-auto">/{{ $article->slug }}</p>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Card Actions -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-auto">
                <div class="row align-items-center">
                    @if(auth()->user()->can('kb.edit') || auth()->user()->can('kb.admin') || auth()->id() === $article->user_id)

                        <!-- ------------------------------------------------- -->
                        <!-- Edit Button -->
                        <!-- ------------------------------------------------- -->
                        <div class="col-auto">
                            <a href="{{ route('kb.article-form', ['articleId' => $article->id]) }}" class="btn btn-info">Edit</a>
                        </div>
    
                        <!-- ------------------------------------------------- -->
                        <!-- Delete Button whit confirm alert -->
                        <!-- ------------------------------------------------- -->
                        <div class="col-auto">
                            <form action="{{ route('kb.delete', $article->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>

                    @endif

                </div>
            </div> <!-- Card Actions END -->

        </div>
    </div> <!-- Card Header END -->

    <!-- ------------------------------------------------- -->
    <!-- Card Body -->
    <!-- ------------------------------------------------- -->
    <div class="card-body">
        <p>{{ $article->content }}</p>
    </div>

    <!-- ------------------------------------------------- -->
    <!-- Card Footer -->
    <!-- ------------------------------------------------- -->
    <div class="card-footer">
        <div class="row justify-content-between">

            <!-- ------------------------------------------------- -->
            <!-- Category and Status -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6">
                <div class="row">

                    <!-- ------------------------------------------------- -->
                    <!-- Category -->
                    <!-- ------------------------------------------------- -->
                    @if($article->category)
                        <p class="col bi bi-tag-fill"> {{ $article->category->name }}</p>
                    @else
                        <p class="col bi bi-tag"> No Category</p>
                    @endif

                    <!-- ------------------------------------------------- -->
                    <!-- Status -->
                    <!-- ------------------------------------------------- -->
                    <p class="col bi bi-eye"> {{ ucfirst($article->status) }}</p>

                    <!-- ------------------------------------------------- -->
                    <!-- author -->
                    <!-- ------------------------------------------------- -->
                    <p class="col bi bi-person"> {{ $article->user ? $article->user->name : 'Unknown Author' }}</p>
                </div>
            </div>
    
            <!-- ------------------------------------------------- -->
            <!-- Buttons to the right START -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6">
                <div class="row justify-content-end">

                    <!-- ------------------------------------------------- -->
                    <!-- Back Button -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-auto">
                        <div class="row">
                            <a href="{{ route('kb.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>

                </div>
            </div> <!-- Buttons to the right END -->
        </div>
    </div> <!-- Card Footer END -->
    
</div> <!-- Card END -->