<form wire:submit.prevent="searchCustomer">
    <!-- Search Form -->
    <div class="row">
        <div class="col-md">
            <label for="email" class="form-label fw-bold">E-post:</label>
            <input type="text" wire:model="email" class="form-control" id="email" placeholder="ola@nordmann.no">
        </div>
        <div class="col-md">
            <label for="tel" class="form-label fw-bold">Telefon:</label>
            <input type="tel" wire:model="tel" class="form-control" id="tel" placeholder="95845119">
        </div>
        <div class="col-md">
            <label for="name" class="form-label fw-bold">Navn:</label>
            <input type="text" wire:model="name" class="form-control" id="name" placeholder="Ola Nordmann">
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Søk</button>
</form>

<!-- Search Result -->
@if($contactData)
    <div class="mt-4">
        <h4>Kunde funnet:</h4>
        <p>Navn: {{ $contactData['FirstName'] }}</p>
        <p>E-post: {{ $contactData['EmailAddress'] }}</p>
        <p>Telefon: {{ $contactData['PhoneMobile'] }}</p>
        <button wire:click="fetchServiceItem" class="btn btn-secondary">Sjekk kontrakt</button>
    </div>
@endif

<!-- Service Item Result -->
@if($serviceItemData)
    <div class="mt-4">
        <h4>Aktiv kontrakt:</h4>
        <p>Navn: {{ $serviceItemData['ServiceItemName'] }}</p>
        <p>ID: {{ $serviceItemData['ServiceItemId'] }}</p>
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif
