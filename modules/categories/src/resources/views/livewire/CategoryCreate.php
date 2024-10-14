<!-- Form for å legge til nye kategorier -->
<form wire:submit.prevent="addCategory">
    <input type="text" wire:model="newCategory" placeholder="New category name">
    <button type="submit">Add Category</button>
</form>
