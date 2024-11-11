<!-- ------------------------------------------------- -->
<!-- 
    This Blade component represents a button that can be used for both saving and updating tasks.
    Usage:
    - Include this component in your Blade template where you need a save/update button.
    - Customize the button text and functionality as needed by passing appropriate attributes.
    Example:
    x-save-update-button task= $ task
-->
<!-- ------------------------------------------------- -->

<button
    type="{{ $type ?? 'button' }}"
    class="{{ $class ?? 'btn btn-success' }} {{ $ico ?? 'bi bi-arrow-repeat' }}">
    {{ $slot->isEmpty() ? ' Save' : $slot }}
</button>