<div>
    <form class="row">

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Venstre side -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <div class="col-lg-6">


            <!-- -------------------------------------------------------------------------------------------------- -->
            <!-- Kunde detaljer -->
            <!-- Kun nødvendig hvis bedrift -->
            <!-- -------------------------------------------------------------------------------------------------- -->
            @if($business == true)
                <div class="container m-3 border-bottom">

                    <!-- ------------------------------------------------- -->
                    <!-- Seksjons header -->
                    <!-- ------------------------------------------------- -->
                    <div class="row">
                        <h4>Kunde detaljer</h4>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Organisasjonsnummer -->
                    <!-- Skal brukes til å søke i Tripletex om kunden allerede er registrert -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3">
                        <div class="mb-3">
                            <label for="orgNr" class="form-label fw-bold">Organisasjons nummer:</label>
                            <input type="number" class="form-control" id="orgNr"
                                wire:model.debounce.500ms="orgNr"
                                wire:change="checkOrgNr"
                                placeholder="11 siffer">
                                @error('orgNr') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Bedrifts navn -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3">
                        <div class="mb-3">
                            <label for="orgName" class="form-label fw-bold">Organisasjons navn:</label>
                            <input type="text" class="form-control" id="orgName" wire:model="orgName" placeholder="Bedriftens navn">
                            @error('orgName') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>
            @endif

            <!-- -------------------------------------------------------------------------------------------------- -->
            <!-- Kunde kontakt -->
            <!-- -------------------------------------------------------------------------------------------------- -->
            <div class="container m-3 border-bottom">

                    <!-- ------------------------------------------------- -->
                    <!-- Seksjons header -->
                    <!-- ------------------------------------------------- -->
                    <div class="row">
                        <h4>Kontakt informasjon</h4>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Kontakt navn -->
                    <!-- Også kundenavn hvis privat -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3">
                        <div class="mb-3">
                            <label for="kontaktNavn" class="form-label fw-bold">Navn:</label>
                            <input type="text" class="form-control" id="kontaktNavn" wire:model="kontaktNavn" placeholder="Kundens navn">
                            @error('kontaktNavn') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Kontakt epost -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3">
                        <div class="mb-3">
                            <label for="kontaktEpost" class="form-label fw-bold">E-post:</label>
                            <input type="text" class="form-control" id="kontaktEpost" wire:model="kontaktEpost" placeholder="ola@nordman.no">
                            @error('kontaktEpost') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Kontakt epost -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3">
                        <div class="mb-3">
                            <label for="kontaktTlf" class="form-label fw-bold">Tlf:</label>
                            <input type="text" class="form-control" id="kontaktTlf" wire:model="kontaktTlf" placeholder="12345678">
                            @error('kontaktTlf') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
            </div>

        </div>

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Høyre side -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <div class="col-lg-6">

            <!-- -------------------------------------------------------------------------------------------------- -->
            <!-- Adresse -->
            <!-- -------------------------------------------------------------------------------------------------- -->
            <div class="container m-3 border-bottom">

                <!-- ------------------------------------------------- -->
                <!-- Seksjons header -->
                <!-- ------------------------------------------------- -->
                <div class="row">
                    <h4>Besøks adresse</h4>
                </div>

                <!-- ------------------------------------------------- -->
                <!-- Kontakt navn -->
                <!-- Også kundenavn hvis privat -->
                <!-- ------------------------------------------------- -->
                <div class="row mt-3">
                    <div class="mb-3">
                        <label for="adrAdresse" class="form-label fw-bold">Adresse:</label>
                        <input type="text" class="form-control" id="adrAdresse" wire:model.lazy="adrAdresse" placeholder="Adresse">
                        @error('adrAdresse') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ------------------------------------------------- -->
                <!-- Postnummer og sted -->
                <!-- ------------------------------------------------- -->
                <div class="row mt-3 mb-3">

                    <!-- ------------------------------------------------- -->
                    <!-- Postnummer-->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-4">
                        <div class="mb-1">
                            <label for="adrPostnr" class="form-label fw-bold">Postnummer:</label>
                            <input type="number" class="form-control" id="adrPostnr" wire:model.lazy="adrPostnr" wire:model.lazy="adrSted" placeholder="7710">
                            @error('adrPostnr') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Sted -->
                    <!-- ------------------------------------------------- -->
                    <div class="col-md-8">
                        <div class="mb-1">
                            <label for="adrSted" class="form-label fw-bold">Sted:</label>
                            <input type="text" class="form-control" id="adrSted" wire:model.lazy="adrSted" placeholder="Stedsnavn">
                            @error('adrSted') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>

            </div>


            <!-- -------------------------------------------------------------------------------------------------- -->
            <!-- Faktura Adresse -->
            <!-- -------------------------------------------------------------------------------------------------- -->
            <div class="container m-3">

                <!-- ------------------------------------------------- -->
                <!-- Seksjons header -->
                <!-- ------------------------------------------------- -->
                <div class="row">
                    <h4>Faktura adresse</h4>
                </div>

                <!-- ------------------------------------------------- -->
                <!-- Samme som besøks adresse -->
                <!-- ------------------------------------------------- -->
                <div class="row mt-3">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="sameAdress" wire:click="toggleSameAs">
                            
                            <label class="form-check-label" for="sameAdress">Samme som besøksadresse</label>
                        </div>
                    </div>
                </div>

                @if ($showFaktAdresse == true)
                    <!-- ------------------------------------------------- -->
                    <!-- Kontakt navn -->
                    <!-- Også kundenavn hvis privat -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3">
                        <div class="mb-3">
                            <label for="faktAdresse" class="form-label fw-bold">Adresse:</label>
                            <input type="text" class="form-control" id="faktAdresse" wire:model="faktAdresse" placeholder="Adresse">
                        </div>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- Postnummer og sted -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3 mb-3">

                        <!-- ------------------------------------------------- -->
                        <!-- Postnummer-->
                        <!-- ------------------------------------------------- -->
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label for="faktPostnr" class="form-label fw-bold">Postnummer:</label>
                                <input type="number" class="form-control" id="faktPostnr" wire:model="faktPostnr" placeholder="7700">
                            </div>
                        </div>

                        <!-- ------------------------------------------------- -->
                        <!-- Sted -->
                        <!-- ------------------------------------------------- -->
                        <div class="col-md-8">
                            <div class="mb-1">
                                <label for="faktSted" class="form-label fw-bold">Sted:</label>
                                <input type="text" class="form-control" id="faktSted" wire:model="faktSted" placeholder="Stedsnavn">
                            </div>
                        </div>

                    </div>
                @endif

                <!-- ------------------------------------------------- -->
                <!-- EHF - Kun hvis bedrift -->
                <!-- ------------------------------------------------- -->
                @if($business == true)
                    <div class="row mt-3">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="ehf" wire:model="ehf">
                                <label class="form-check-label" for="ehf">EHF faktura</label>
                            </div>
                        </div>
                    </div>

                    <!-- ------------------------------------------------- -->
                    <!-- E-post -->
                    <!-- ------------------------------------------------- -->
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div class="mb-1">
                                <label for="faktepost" class="form-label fw-bold">Faktura / Påmindelse e-post:</label>
                                <input type="mail" class="form-control" id="faktepost" wire:model="faktepost" placeholder="post@bedrift.no">
                            </div>
                        </div>
                    </div>
                @endif

            </div>


        </div>

    </form>

    <button wire:click="validateForm"> Send inn </button>
</div>