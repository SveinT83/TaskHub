<div>

    <!-- ------------------------------------------------- -->
    <!-- KB artickles list CARD -->
    <!-- ------------------------------------------------- -->
    <div class="card mt-3">

        <!-- ------------------------------------------------- -->
        <!-- Card Header -->
        <!-- ------------------------------------------------- -->
        <div class="card-header text-bg-primary">
            <div class="d-flex justify-content-between">
                <h2 class="card-title">KB Articles</h2>
            </div>
        </div>

        

        <!-- ------------------------------------------------- -->
        <!-- Card Body -->
        <!-- ------------------------------------------------- -->
        <div class="card-body bg-body-tertiary">

            <!-- ------------------------------------------------- -->
            <!-- Artickle List -->
            <!-- ------------------------------------------------- -->
            <div class="row justify-content-center">
                @if($articles->isNotEmpty())
                    @foreach($articles as $article)
                        <div class="col-md-5 col-lg-3 col-xl-2 m-1 d-flex">
                            <a class="card h-100 w-100" href="{{ route('kb.show', $article->id) }}">
                                <div class="card-header bg-secondary-subtle">
                                    <b>{{ $article->title }}</b>
                                </div>

                                <div class="card-body">
                                    <p>{{ \Illuminate\Support\Str::limit($article->content, 100) }}</p>
                                </div>

                                <div class="card-footer" style="font-size: 10px;">
                                    <div class="d-flex justify-content-between">
                                        @if($article->category)
                                            <p class="bi bi-tag-fill">{{ $article->category->name }}</p>
                                        @else
                                            <p class="bi bi-tag">No Category</p>
                                        @endif

                                        <p class="bi bi-eye"> {{ $article->status }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info" role="alert">
                        <p>No articles available!</p>
                        <a href="{{ route('kb.article-form') }}" class="btn btn-link">Create the first one</a>
                    </div>
                @endif
            </div>
            
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Card Footer -->
        <!-- ------------------------------------------------- -->
        <div class="card-footer">

        </div> <!-- Card Footer END -->

    </div> <!-- Card END -->

</div>
