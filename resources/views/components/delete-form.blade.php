<form action="{{ $route }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-sm btn-danger bi bi-x"> {{ $slot->isEmpty() ? ' Delete' : $slot }}</button>
</form>
