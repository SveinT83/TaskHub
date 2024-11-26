<div>
    <form wire:submit.prevent="save">
        @csrf   

        <!-- Vennlig navn og beskrivelse -->
        <x-card-secondary title="Frendly name and decription">
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Name:</label>
                <input type="text" class="form-control" id="name" name="name" wire:model="name" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">description</label>
                <textarea class="form-control" id="description" name="description" wire:model="description"></textarea>
            </div>
        </x-card>

        <div class="row mt-3">
            <div class="col-md-6">

                <!-- SMTP-innstillinger -->
                <x-card-secondary title="SMTP-settings">
                        <div class="mb-3">
                            <label for="smtp_host" class="form-label fw-bold">SMTP Host:</label>
                            <input type="text" class="form-control" id="smtp_host" name="smtp_host" wire:model="smtp_host" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="smtp_port" class="form-label fw-bold">SMTP Port:</label>
                                <select class="form-select" id="smtp_port" name="smtp_port" wire:model="smtp_port">
                                    <option value="465">465</option>
                                    <option value="587">587</option>
                                    <option value="2525">2525</option>
                                    <option value="25">25</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="smtp_encryption" class="form-label fw-bold">SMTP Encryption:</label>
                            
                                <select class="form-select" id="smtp_encryption" name="smtp_encryption" wire:model="smtp_encryption">
                                    <option value="SSL/TLS">SSL/TLS</option>
                                    <option value="STARTTLS">STARTTLS</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="smtp_username" class="form-label fw-bold">SMTP Username:</label>
                            <input type="text" class="form-control" id="smtp_username" name="smtp_username" wire:model="smtp_username" required>
                        </div>
                        <div class="mb-3">
                            <label for="smtp_password" class="form-label fw-bold">SMTP Password:</label>
                            <input type="password" class="form-control" id="smtp_password" name="smtp_password" wire:model="smtp_password">
                        </div>
                </x-card>
            </div>

            <div class="col-md-6">

                <!-- IMAP-innstillinger -->
                <x-card-secondary title="IMAP-innstillinger">
                    <div class="mb-3">
                        <label for="imap_host" class="form-label fw-bold">IMAP Host:</label>
                        <input type="text" class="form-control" id="imap_host" name="imap_host" wire:model="imap_host" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="imap_port" class="form-label fw-bold">IMAP Port:</label>

                            <select class="form-select" id="imap_port" name="imap_port" wire:model="imap_port">
                                <option value="143">143</option>
                                <option value="993">993</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="imap_encryption" class="form-label fw-bold">IMAP Encryption:</label>

                            <select class="form-select" id="imap_encryption" name="imap_encryption" wire:model="imap_encryption">
                                <option value="SSL/TLS">SSL/TLS</option>
                                <option value="STARTTLS">STARTTLS</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="imap_username" class="form-label fw-bold">IMAP Username:</label>
                        <input type="text" class="form-control" id="imap_username" name="imap_username" wire:model="imap_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="imap_password" class="form-label fw-bold">IMAP Password:</label>
                        <input type="password" class="form-control" id="imap_password" name="imap_password" wire:model="imap_password">
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Er standard konto -->
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                            <label class="form-check-label" for="is_default">Set as default</label>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ $isEditMode ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
