<div class="card">
    <div class="card-header text-bg-primary">
        <h5>Comments</h5>
    </div>
    <div class="card-body bg-body-tertiary">
        <!-- Vis eksisterende kommentarer -->
        @forelse($task->comments as $comment)
            <div class="card mt-1">

                <div class="card-header">
                    <div class="row">
                        <strong class="col-lg-10">{{ $comment->user->name }}</strong>
                        <small class="col-lg-2">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <div class="card-body">
                    <p class="card-text">{{ $comment->comment }}</p>

                    <!-- Show delete button only if the user has permission -->
                    <form action="{{ route('tasks.comment.delete', [$task->id, $comment->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm bi bi-trash"> Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p>No comments yet.</p>
        @endforelse

    </div>

    <div class="card-footer">
        <!-- Skjema for å legge til kommentar -->
        <form action="{{ route('tasks.comment', $task->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="comment" class="form-label">Add a comment:</label>
                <textarea name="comment" id="comment" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
