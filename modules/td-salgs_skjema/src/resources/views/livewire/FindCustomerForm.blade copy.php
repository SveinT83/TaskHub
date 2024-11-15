<div>
    <form>
        <div class="row">
            <div class="col-md">
                <label for="email" class="form-label fw-bold">E-post:</label>
                <input type="text" wire:model="email" class="form-control" id="email" placeholder="ola@nordmann.no">
            </div>
            <div class="col-md">
                <label for="tel" class="form-label fw-bold">Telefon:</label>
                <input type="tel" wire:model="tel" class="form-control" id="tel" placeholder="12345678">
            </div>
            <div class="col-md">
                <label for="name" class="form-label fw-bold">Navn:</label>
                <input type="text" wire:model="name" class="form-control" id="name" placeholder="Ola Nordmann">
            </div>
        </div>

        <button wire:click="searchCustomer">Søk</button>

        <!-- Vis spinneren når søket er i gang -->
        @if($isLoading)
            <div class="spinner-border text-primary ms-3" role="status">
                <span class="visually-hidden">Laster...</span>
            </div>
        @endif
    </form>

    @if($contactData)
        <div class="mt-4">
            <h4>Kunde funnet:</h4>
            <p>Navn: {{ $contactData['FirstName'] }}</p>
            <p>E-post: {{ $contactData['EmailAddress'] }}</p>
            <p>Telefon: {{ $contactData['PhoneMobile'] }}</p>
        </div>
    @endif

    @if($customerInfo)
        <div class="mt-4">
            <h4>Kundeinformasjon:</h4>
            <p>Kundenavn: {{ $customerInfo['CustomerName'] ?? 'Ikke tilgjengelig' }}</p>
            <p>Kundenummer: {{ $customerInfo['CustomerId'] }}</p>
        </div>
    @endif

    @if($serviceItemData)
        <div class="mt-4">
            <h4>Service Level:</h4>
            <p>Service Item: {{ $serviceItemData['ServiceItemName'] }}</p>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>
