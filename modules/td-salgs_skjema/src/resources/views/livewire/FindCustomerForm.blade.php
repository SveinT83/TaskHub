<!-- -------------------------------------------------------------------------------------------------- -->
<!-- COMPOMENT -->
<!-- src/Livewire/FindCustomerForm.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

<div>

    @if(!$contactData)
        <!-- ------------------------------------------------- -->
        <!-- Search form -->
        <!-- ------------------------------------------------- -->
        <form class="row">
            <div class="col-md">
                <label for="email" class="form-label fw-bold">E-post:</label>
                <input type="text" wire:model="email" class="form-control" id="email" placeholder="ola@nordmann.no" required>
            </div>
            <div class="col-md">
                <label for="tel" class="form-label fw-bold">Telefon:</label>
                <input type="tel" wire:model="tel" class="form-control" id="tel" placeholder="12345678" required>
            </div>
            <div class="col-md">
                <label for="name" class="form-label fw-bold">Navn:</label>
                <input type="text" wire:model="name" class="form-control" id="name" placeholder="Ola Nordmann" required>
            </div>
        </form>

        <!-- ------------------------------------------------- -->
        <!-- Button to search for customer -->
        <!-- ------------------------------------------------- -->
        <div class="row mt-3">
            <button wire:click="searchCustomer" wire:loading.remove class="btn btn-primary">Søk</button>
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Spinner while loading -->
        <!-- ------------------------------------------------- -->
        <div wire:loading class="spinner-border text-primary ms-3" role="status">
            <span class="visually-hidden">Laster...</span>
        </div>
    @endif

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Search results -->
    <!-- -------------------------------------------------------------------------------------------------- -->

    <!-- ------------------------------------------------- -->
    <!-- Show warning and error messages -->
    <!-- ------------------------------------------------- -->
    <div class="row mt-3">
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

    <!-- ------------------------------------------------- -->
    <!-- Show results -->
    <!-- ------------------------------------------------- -->
    @if($contactData)
        <div class="container mt-3">

            <!-- ------------------------------------------------- -->
            <!-- Customer data -->
            <!-- ------------------------------------------------- -->
            <div class="row text-bg-light p-1">

                <!-- ------------------------------------------------- -->
                <!-- If contact info -->
                <!-- ------------------------------------------------- -->
                @if($contactData)
                    <div class="col-lg mt-3">
                        <h4>Bruker:</h4>

                        <ul class="list-group list-group-flush mt-3">
                            <li class="list-group-item text-bg-light"><p><b>Navn: </b>{{ $contactData['FirstName'] ?? 'Mangler navn' }}</p></li>
                            <li class="list-group-item text-bg-light"><p><b>E-post: </b>{{ $contactData['EmailAddress'] ?? 'Mangler e-post' }}</p></li>
                            <li class="list-group-item text-bg-light"><p><b>Telefon: </b>{{ $contactData['PhoneMobile']?? 'Mangler telefon'  }}</p></li>
                        </ul>
                    </div>
                @endif

                <!-- ------------------------------------------------- -->
                <!-- If customer info -->
                <!-- ------------------------------------------------- -->
                @if($customerInfo)
                    <div class="col-lg mt-3">
                        <h4>Kundeinformasjon:</h4>

                        <ul class="list-group list-group-flush mt-3">
                            <li class="list-group-item text-bg-light"><p><b>Kundenummer: </b>{{ $customerInfo['customerNumber'] ?? 'N/A'}}</p></li>
                            <li class="list-group-item text-bg-light"><p><b>Kundenavn: </b>{{ $customerInfo['customerName'] ?? 'N/A' }}</p></li>
                            <li class="list-group-item text-bg-light"><p><b>Full navn: </b>{{ $customerInfo['fullName'] ?? 'N/A' }}</p></li>
                        </ul>
                    </div>
                @endif

                <!-- ------------------------------------------------- -->
                <!-- If service item data -->
                <!-- ------------------------------------------------- -->
                @if($serviceItemData)
                    <div class="col-lg mt-3">
                        <h4>Service Level:</h4>
                        <ul class="list-group list-group-flush mt-3">
                            <li class="list-group-item text-bg-light"><p><b>Service Item: </b>{{ $serviceItemData['ServiceItemName'] }}</p></li>
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
