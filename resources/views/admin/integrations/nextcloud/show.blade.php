<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Integrations/Nextcloud/NextcloudController.php -->
<!-- filepath: /var/Projects/TaskHub/Dev/resources/views/admin/integrations/nextcloud/show.blade.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

<!-- ------------------------------------------------- -->
<!-- Show Nextcloud Integration -->
<!-- ------------------------------------------------- -->
@extends('layouts.app')
@section('title', 'Home')

<!-- ------------------------------------------------- -->
<!-- Include Header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Nextcloud Integration"></x-page-header>
@endsection

<!-- ------------------------------------------------- -->
<!-- Content Section -->
<!-- ------------------------------------------------- -->
@section('content')
<div class="container mt-3">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ------------------------------------------------- -->
    <!-- Card Start -->
    <!-- ------------------------------------------------- -->
    <x-card-secondary title="Connect / Disconnect">

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Instructions for Nextcloud OAuth2 Setup -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <div class="alert alert-info mb-4">
            <h6><i class="bi bi-info-circle"></i> Nextcloud OAuth2 Setup Instructions</h6>
            <ol class="mb-0">
                <li>Go to your Nextcloud admin settings → Security → OAuth 2.0</li>
                <li>Click "Add client" to create a new OAuth2 application</li>
                <li>Set a name (e.g., "TaskHub Integration")</li>
                <li>Copy the <strong>Redirect URI</strong> from below into the "Redirection URI" field</li>
                <li>Save and copy the generated <strong>Client ID</strong> and <strong>Client Secret</strong></li>
                <li>Fill in the form below with your Nextcloud details</li>
            </ol>
        </div>

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Header row, shows status of Nextcloud -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <div class="row">

            <!-- ------------------------------------------------- -->
            <!-- Show status of Nextcloud -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6">

                <!-- If Nextcloud credentials are set -->
                @if($user && $user->nextcloud_token)
                    <p>Nextcloud is activated.</p>
                @else
                    <p>Nextcloud is not activated.</p>
                @endif
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Toggle Nextcloud -->
            <!-- ------------------------------------------------- -->
            <div class="col-md-6 text-end">
                @if($credentials && $credentials->baseurl && $credentials->clientid && $credentials->clientsecret && $credentials->redirecturi)
                    <form class="" action="{{ route('nextcloud.toggle') }}" method="POST">
                        @csrf
                        @if($isNextcloudActive)
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-pause-circle"></i> Deactivate Nextcloud
                            </button>
                        @else
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-play-circle"></i> Activate Nextcloud
                            </button>
                        @endif
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Please configure all credentials before activating.
                    </div>
                @endif
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Form to update Nextcloud credentials -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <form class="row mt-3" action="{{ route('nextcloud.updateCredentials') }}" method="POST">
            @csrf

            <!-- ------------------------------------------------- -->
            <!-- Base URL -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="baseurl">Base URL</label>
                <input type="text" class="form-control" id="baseurl" name="baseurl" value="{{ $credentials->baseurl ?? '' }}" placeholder="Enter Base URL" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Client ID -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="clientid">Client ID</label>
                <input type="text" class="form-control" id="clientid" name="clientid" value="{{ $credentials->clientid ?? '' }}" placeholder="Enter Client ID" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Client Secret -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="clientsecret">Client Secret</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="clientsecret" name="clientsecret" 
                           value="{{ ($credentials && $credentials->clientsecret) ? '••••••••••••' : '' }}" 
                           placeholder="Enter Client Secret" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('clientsecret')">
                        <i class="bi bi-eye" id="clientsecret-icon"></i>
                    </button>
                </div>
                <small class="text-muted">Leave unchanged to keep existing secret</small>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Redirect URI -->
            <!-- ------------------------------------------------- -->
            <div class="form-group mb-3">
                <label for="redirecturi">Redirect URI</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="redirecturi" name="redirecturi" 
                           value="{{ $credentials->redirecturi ?? $defaultRedirectUri }}" 
                           placeholder="Redirect URI will be auto-generated" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('redirecturi')" title="Copy to clipboard">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> 
                    This URI is automatically generated based on your TaskHub installation. 
                    Copy and paste this into your Nextcloud OAuth2 app configuration.
                </small>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Save/Update Button -->
            <!-- ------------------------------------------------- -->
            <button type="submit" class="btn btn-primary">Save/Update</button>
        </form>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- End of Card and container and Section -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    </x-card-secondary>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function copyToClipboard(fieldId) {
    const field = document.getElementById(fieldId);
    
    // Select the text
    field.select();
    field.setSelectionRange(0, 99999); // For mobile devices
    
    // Copy to clipboard
    navigator.clipboard.writeText(field.value).then(function() {
        // Show success feedback
        const button = field.nextElementSibling.querySelector('i');
        const originalClass = button.className;
        button.className = 'bi bi-check';
        
        setTimeout(function() {
            button.className = originalClass;
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy to clipboard');
    });
}
</script>
@endsection