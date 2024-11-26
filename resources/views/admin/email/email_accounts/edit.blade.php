@extends('layouts.app')

@section('pageHeader')
    <div class="row align-items-center justify-content-between">
        <div class="col-md-2 mt-1">
            <h1>Add account form</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="container mt-3">

    <form action="{{ route('email_accounts.update', $emailAccount->id) }}" method="POST">
        @csrf
        @method('PUT')    

        <!-- Vennlig navn og beskrivelse -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Frendly name and decription</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $emailAccount->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">description</label>
                            <textarea class="form-control" id="description" name="description" value="{{ $emailAccount->description }}"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- SMTP-innstillinger -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>SMTP-settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="smtp_host" class="form-label fw-bold">SMTP Host:</label>
                            <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="{{ $emailAccount->smtp_host }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="smtp_port" class="form-label fw-bold">SMTP Port:</label>
                                <select class="form-select"  id="smtp_port" name="smtp_port" aria-label="Default select example">
                                    <option value="{{ $emailAccount->smtp_port }}" selected>{{ $emailAccount->smtp_port }}</option>
                                    <option value="465">465</option>
                                    <option value="587">587</option>
                                    <option value="2525">2525</option>
                                    <option value="25">25</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="smtp_encryption" class="form-label fw-bold">SMTP Encryption:</label>
                            
                                <select class="form-select"  id="smtp_encryption" name="smtp_encryption" aria-label="Default select example">
                                    <option value="{{ $emailAccount->smtp_port }}" selected>{{ $emailAccount->smtp_encryption }}</option>
                                    <option value="SSL/TLS">SSL/TLS</option>
                                    <option value="STARTTLS">STARTTLS</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="smtp_username" class="form-label fw-bold">SMTP Username:</label>
                            <input type="text" class="form-control" id="smtp_username" name="smtp_username" value="{{ $emailAccount->smtp_username }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="smtp_password" class="form-label fw-bold">SMTP Password:</label>
                            <input type="password" class="form-control" id="smtp_password" name="smtp_password" placeholder="Leave blank to keep the current password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-3">
                <!-- IMAP-innstillinger -->
                <div class="card">
                    <div class="card-header">
                        <h3>IMAP-innstillinger</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="imap_host" class="form-label fw-bold">IMAP Host:</label>
                            <input type="text" class="form-control" id="imap_host" name="imap_host" value="{{ $emailAccount->imap_host }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="imap_port" class="form-label fw-bold">IMAP Port:</label>

                                <select class="form-select"  id="imap_port" name="imap_port" aria-label="Default select example">
                                    <option value="{{ $emailAccount->smtp_port }}" selected>{{ $emailAccount->imap_port }}</option>
                                    <option value="143">143</option>
                                    <option value="993">993</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="imap_encryption" class="form-label fw-bold">IMAP Encryption:</label>

                                <select class="form-select"  id="imap_encryption" name="imap_encryption" aria-label="Default select example">
                                    <option value="{{ $emailAccount->smtp_port }}" selected>{{ $emailAccount->imap_encryption }}</option>
                                    <option value="SSL/TLS">SSL/TLS</option>
                                    <option value="STARTTLS">STARTTLS</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="imap_username" class="form-label fw-bold">IMAP Username:</label>
                            <input type="text" class="form-control" id="imap_username" name="imap_username" value="{{ $emailAccount->imap_username }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="imap_password" class="form-label fw-bold">IMAP Password:</label>
                            <input type="password" class="form-control" id="imap_password" name="imap_password" placeholder="Leave blank to keep the current password">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Er standard konto -->
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ $emailAccount->is_default ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Set as default</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Lagre</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
