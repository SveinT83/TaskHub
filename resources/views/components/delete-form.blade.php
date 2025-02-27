<!--
This component renders a delete form with a button. When the button is clicked, it sends a DELETE request to the specified route. If a warning message is provided, a confirmation dialog will appear before submitting the form.

Usage:
<delete-form route=route('your.route.name') warning="Are you sure you want to delete this item?">
    Optional button text
</delete-form>

Parameters:
- route: The route to which the DELETE request will be sent.
- warning (optional): A confirmation message that will be shown before submitting the form.
- slot (optional): Custom text to display on the delete button. If not provided, the default text 'Delete' will be used.
-->

<form action="{{ $route }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="btn btn-sm btn-danger bi bi-x"
        @if(!empty($warning)) onclick="return confirm('{{ $warning }}')" @endif>
        {{ $slot->isEmpty() ? ' Delete' : $slot }}
    </button>
</form>
