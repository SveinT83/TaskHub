<div>

    <!-- ------------------------------------------------- -->
    <!-- KB artickles list CARD -->
    <!-- ------------------------------------------------- -->
    <div class="card mt-3">

        <!-- ------------------------------------------------- -->
        <!-- Card Header -->
        <!-- ------------------------------------------------- -->

        

        <!-- ------------------------------------------------- -->
        <!-- Card Body -->
        <!-- ------------------------------------------------- -->
        <div class="card-body bg-body-tertiary">

            <!-- ------------------------------------------------- -->
            <!-- Artickle List -->
            <!-- ------------------------------------------------- -->
            @if($articles->isNotEmpty())
                @foreach($articles as $article)
                    <div class="article-item">
                        <a href="{{ route('kb.show', $article->id) }}">{{ $article->title }}</a>
                        <p>{{ $article->excerpt }}</p>
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

</div>
